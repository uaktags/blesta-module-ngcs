<?php

class Ngcs extends Module {

    private static $version = "0.1";
    private static $authors = array(array('name' => "Tim Garrity", 'url' => "https://www.timgarrity.me"));

    public function __construct() {
        $this->loadConfig(dirname(__FILE__) . DS . "config.json");
        Loader::loadComponents($this, array("Input"));
        Language::loadLang("lang", null, dirname(__FILE__) . DS . "language" . DS);
        Loader::loadHelpers($this, array("Html"));
    }

    public function getName() {
        return Language::_("ngcs.name", true);
    }

    public function getVersion() {
        return self::$version;
    }

    public function getAuthors() {
        return self::$authors;
    }

    public function getAdminTabs($package) {
        return array();
    }

    public function getClientTabs($package) {
        return array(
            'managementoptions' => Language::_("ngcs.managementoptions", true),
            'actionshistory' => Language::_("ngcs.actionshistory", true),
        );
    }

    public function actionshistory($package, $service, array $get = null, array $post = null, array $files = null) {
        $this->view = new View("actionshistory", "default");
        $this->view->base_uri = $this->base_uri;
        Loader::loadHelpers($this, array("Form", "Html"));
        $service_fields = $this->serviceFieldsToObject($service->fields);
        $module_row = $this->getModuleRow($package->module_row);
        $api = $this->getApi($module_row->meta->apiKey);

        $this->view->set("module_row", $module_row);
        $this->view->set("service_fields", $service_fields);

        $this->view->setDefaultView("components" . DS . "modules" . DS . "ngcs" . DS);
        return $this->view->fetch();
    }

    public function managementoptions($package, $service, array $get = null, array $post = null, array $files = null) {
        $this->view = new View("managementoptions", "default");
        $this->view->base_uri = $this->base_uri;
        Loader::loadHelpers($this, array("Form", "Html"));
        $service_fields = $this->serviceFieldsToObject($service->fields);
        $module_row = $this->getModuleRow($package->module_row);
        $api = $this->getApi($module_row->meta->apiKey);
        Loader::loadModels($this, array("Services"));

        if (isset($post['power_cycle'])) {
            $result = $api->getPostResults("droplets/{$service_fields->droplet_id}/actions", array("type" => "power_cycle"));
            if (isset($result->message)) {
                $fa = array(
                    0 => array(
                        "result" => isset($result->message) ? str_replace("Droplet", "Server", $result->message) : Language::_("ngcs.empty_invalid_values", true)
                    )
                );
                $this->Input->setErrors($fa);
            }
        }

        if (isset($post['shutdown'])) {
            $result = $api->getPostResults("droplets/{$service_fields->droplet_id}/actions", array("type" => "shutdown"));
            if (isset($result->message)) {
                $fa = array(
                    0 => array(
                        "result" => isset($result->message) ? str_replace("Droplet", "Server", $result->message) : Language::_("ngcs.empty_invalid_values", true)
                    )
                );
                $this->Input->setErrors($fa);
            }
        }

        if (isset($post['power_on'])) {
            $result = $api->getPostResults("droplets/{$service_fields->droplet_id}/actions", array("type" => "power_on"));
            if (isset($result->message)) {
                $fa = array(
                    0 => array(
                        "result" => isset($result->message) ? str_replace("Droplet", "Server", $result->message) : Language::_("ngcs.empty_invalid_values", true)
                    )
                );
                $this->Input->setErrors($fa);
            }
        }


        $ip_address = null;
        $droplet_info = $api->getGetResults("droplets/{$service_fields->droplet_id}")->droplet;
        if (!empty($droplet_info->networks->v4)) {
            foreach ($droplet_info->networks->v4 as $ipkey => $ipvalue) {
                if ($droplet_info->networks->v4[$ipkey]->type === "public") {
                    $ip_address = $droplet_info->networks->v4[$ipkey]->ip_address;
                }
            }
        }
        if (!empty($droplet_info->networks->v6)) {

            foreach ($droplet_info->networks->v6 as $ipv6key => $ipv6value) {
                if ($droplet_info->networks->v6[$ipv6key]->type === "public") {
                    $ip_address = $droplet_info->networks->v6[$ipv6key]->ip_address;
                }
            }
        }

        $this->view->set("ip_address", $ip_address);
        $this->view->set("droplet_info", $droplet_info);
        $this->view->set("rebuild_images", $this->getImagesDropdown($package->id));
        $this->view->set("kernels", $this->getkernelDropdown($module_row, $service_fields->droplet_id));
        $this->view->set("restore_snapshots", $this->getsnapshotsDropdown($module_row, $service_fields->droplet_id));
        $this->view->set("restore_images", $this->getRestoreImagesDropdown($module_row, $service_fields->droplet_id));
        $this->view->set("kernel_id", $droplet_info->kernel->id);
        $this->view->set("module_row", $module_row);
        $this->view->set("service_fields", $service_fields);

        $this->view->setDefaultView("components" . DS . "modules" . DS . "ngcs" . DS);
        return $this->view->fetch();
    }

    public function getsnapshotsDropdown($module_row, $droplet_id) {
        $api = $this->getApi($module_row->meta->apiKey);
        $result = $api->getlongGetResults("droplets/{$droplet_id}/snapshots")->snapshots;
        $dp = array();
        foreach ($result as $key => $value) {
            if ($result[$key]->type === "snapshot") {
                $dp[$result[$key]->id] = $result[$key]->name . " - " . $result[$key]->distribution;
            }
        }
        return $dp;
    }

    public function getkernelDropdown($module_row, $droplet_id) {
        $api = $this->getApi($module_row->meta->apiKey);
        $result = $api->getlongGetResults("droplets/{$droplet_id}/kernels")->kernels;
        $dp = array();
        foreach ($result as $key => $value) {
            $dp[$result[$key]->id] = $result[$key]->name;
        }
        return $dp;
    }

    public function getImagesDropdown($p_id) {
        Loader::loadModels($this, array("PackageOptions"));
        $pkgs = $this->PackageOptions->getByPackageId($p_id);
        $array = array();
        foreach ($pkgs as $key => $value) {
            if (isset($pkgs[$key]->name) && isset($pkgs[$key]->type) && $pkgs[$key]->name === "image" && $pkgs[$key]->type === "select") {
                foreach ($pkgs[$key]->values as $vkey => $vvalue) {
                    $array[$pkgs[$key]->values[$vkey]->value] = $pkgs[$key]->values[$vkey]->name;
                }
            }
        }
        return $array;
    }

    public function getRestoreImagesDropdown($module_row, $droplet_id) {
        $api = $this->getApi($module_row->meta->apiKey);
        $result = $api->getlongGetResults("droplets/{$droplet_id}/backups")->backups;
        $dp = array();
        foreach ($result as $key => $value) {
            if ($result[$key]->type === "backup") {
                $dp[$result[$key]->id] = $result[$key]->name . " - " . $result[$key]->distribution;
            }
        }
        return $dp;
    }

    public function moduleRowName() {
        return Language::_("ngcs.module_row", true);
    }

    public function moduleRowNamePlural() {
        return Language::_("ngcs.module_row_plural", true);
    }

    public function moduleGroupName() {
        return Language::_("ngcs.module_group", true);
    }

    public function moduleRowMetaKey() {
        return "name";
    }

    public function getGroupOrderOptions() {
        return array('first' => Language::_("ngcs.order_options.first", true));
    }

    public function selectModuleRow($module_group_id) {
        if (!isset($this->ModuleManager))
            Loader::loadModels($this, array("ModuleManager"));

        $group = $this->ModuleManager->getGroup($module_group_id);

        if ($group) {
            switch ($group->add_order) {
                default:
                case "first":

                    foreach ($group->rows as $row) {
                        return $row->id;
                    }

                    break;
            }
        }
        return 0;
    }

    public function managecloud($package, $service, array $get = null, array $post = null, array $files = null) {
        $this->view = new View("managecloud", "default");
        $this->view->setDefaultView("components" . DS . "modules" . DS . "ngcs" . DS);
        Loader::loadHelpers($this, array("Form", "Html"));
        $row = $this->getModuleRow($package->module_row);
        $api = $this->getApi($row->meta->apiKey);
        $service_fields = $this->serviceFieldsToObject($service->fields);


        $this->view->set("service_fields", $service_fields);
        return $this->view->fetch();
    }

    public function getPackageFields($vars = null) {
        Loader::loadHelpers($this, array("Html"));
        $fields = new ModuleFields();
        $module_row = null;
        if (isset($vars->module_group) && $vars->module_group == "") {

            if (isset($vars->module_row) && $vars->module_row > 0)
                $module_row = $this->getModuleRow($vars->module_row);
            else {

                $rows = $this->getModuleRows();
                if (isset($rows[0]))
                    $module_row = $rows[0];
                unset($rows);
            }
        }
        else {

            $rows = $this->getModuleRows($vars->module_group);
            if (isset($rows[0]))
                $module_row = $rows[0];
            unset($rows);
        }
        $size_options = $this->getSizesDropdown($module_row);

        $sizes = $fields->label(Language::_("ngcs.size", true), "size");
        $sizes->attach($fields->fieldSelect("meta[size]", $size_options, $this->Html->ifSet($vars->meta['size']), array('id' => "size")));
        $fields->setField($sizes);

        return $fields;
    }

    public function getSizesDropdown($module_row) {
        $api = $this->getApi($module_row->meta->apiKey);
        $result = $api->ngcs->Server()->getFixedInstances();
        $dp = array();
        foreach ($result as $key) {
            $dp[$key->id] = $key->name;
        }
        return $dp;
    }

    public function getEmailTags() {
        return array(
            'package' => array('package', 'size'),
            'service' => array('droplet_id', 'servername', 'region', 'image', 'backups', 'ipv6', 'private_networking', 'client_sshkey', 'user_data')
        );
    }

    public function addPackage(array $vars = null) {
        $meta = array();
        foreach ($vars as $key => $value) {
            $meta[] = array(
                'key' => $key,
                'value' => $value,
                'encrypted' => 0
            );
        }
        return $meta;
    }

    public function editPackage($package, array $vars = null) {
        $meta = array();
        foreach ($vars as $key => $value) {
            $meta[] = array(
                'key' => $key,
                'value' => $value,
                'encrypted' => 0
            );
        }
        return $meta;
    }

    public function manageModule($module, array &$vars) {

        $this->view = new View("manage", "default");
        $this->view->base_uri = $this->base_uri;
        $this->view->setDefaultView("components" . DS . "modules" . DS . "ngcs" . DS);


        Loader::loadHelpers($this, array("Form", "Html", "Widget"));

        $this->view->set("module", $module);

        return $this->view->fetch();
    }

    public function manageAddRow(array &$vars) {

        $this->view = new View("add_row", "default");
        $this->view->base_uri = $this->base_uri;
        $this->view->setDefaultView("components" . DS . "modules" . DS . "ngcs" . DS);


        Loader::loadHelpers($this, array("Form", "Html", "Widget"));

        $this->view->set("vars", (object) $vars);
        return $this->view->fetch();
    }

    public function manageEditRow($module_row, array &$vars) {
        $this->view = new View("edit_row", "default");
        $this->view->base_uri = $this->base_uri;
        $this->view->setDefaultView("components" . DS . "modules" . DS . "ngcs" . DS);


        Loader::loadHelpers($this, array("Form", "Html", "Widget"));

        if (empty($vars))
            $vars = $module_row->meta;


        $this->view->set("vars", (object) $vars);
        return $this->view->fetch();
    }

    public function addModuleRow(array &$vars) {
        $meta_fields = array("name", "apiKey");
        $encrypted_fields = array("apiKey");

        $this->Input->setRules($this->getRowRules($vars));


        if ($this->Input->validates($vars)) {


            $meta = array();
            foreach ($vars as $key => $value) {

                if (in_array($key, $meta_fields)) {
                    $meta[] = array(
                        'key' => $key,
                        'value' => $value,
                        'encrypted' => in_array($key, $encrypted_fields) ? 1 : 0
                    );
                }
            }

            return $meta;
        }
    }

    public function editModuleRow($module_row, array &$vars) {
        $meta_fields = array("name", "apiKey");
        $encrypted_fields = array("apiKey");

        $this->Input->setRules($this->getRowRules($vars));


        if ($this->Input->validates($vars)) {


            $meta = array();
            foreach ($vars as $key => $value) {

                if (in_array($key, $meta_fields)) {
                    $meta[] = array(
                        'key' => $key,
                        'value' => $value,
                        'encrypted' => in_array($key, $encrypted_fields) ? 1 : 0
                    );
                }
            }

            return $meta;
        }
    }

    public function deleteModuleRow($module_row) {

    }

    public function getServiceName($service) {
        foreach ($service->fields as $field) {
            if ($field->key == "servername")
                return $field->value;
        }
        return null;
    }

    public function getPackageServiceName($package, array $vars = null) {
        if (isset($vars['servername']))
            return $vars['servername'];
        return null;
    }

    public function getAdminAddFields($package, $vars = null) {
        Loader::loadHelpers($this, array("Html"));
        $fields = new ModuleFields();

        $domain = $fields->label(Language::_("ngcs.servername", true), "servername");
        $domain->attach($fields->fieldText("servername", $this->Html->ifSet($vars->servername, $this->Html->ifSet($vars->servername)), array('id' => "servername")));
        $fields->setField($domain);


        return $fields;
    }

    public function getClientAddFields($package, $vars = null) {
        Loader::loadHelpers($this, array("Html"));
        $fields = new ModuleFields();
        $domain = $fields->label(Language::_("ngcs.servername", true), "servername");
        $domain->attach($fields->fieldText("servername", $this->Html->ifSet($vars->servername, $this->Html->ifSet($vars->servername)), array('id' => "servername")));
        $fields->setField($domain);

        $description = $fields->label(Language::_("ngcs.client_description", true), "clientdescription");
        $description->attach($fields->fieldText("clientdescription", $this->Html->ifSet($vars->clientdescription), array('id' => "clientdescription")));
        $fields->setField($description);
		
		$pass = $fields->label(Language::_("ngcs.serverpass", true), "serverpassword");
        $pass->attach($fields->fieldText("serverpassword", $this->Html->ifSet($vars->serverpassword, $this->Html->ifSet($vars->serverpassword)), array('id' => "serverpassword")));
        $fields->setField($pass);

        return $fields;
    }

    public function getAdminEditFields($package, $vars = null) {
        Loader::loadHelpers($this, array("Html"));
        $fields = new ModuleFields();
        $domain = $fields->label(Language::_("ngcs.servername", true), "servername");
        $domain->attach($fields->fieldText("servername", $this->Html->ifSet($vars->servername, $this->Html->ifSet($vars->servername)), array('id' => "servername")));
        $fields->setField($domain);

        return $fields;
    }

    public function validateService($package, array $vars = null, $edit = false) {
        $rules = array();

        $this->Input->setRules($rules);
        return $this->Input->validates($vars);
    }

    public function addService($package, array $vars = null, $parent_package = null, $parent_service = null, $status = "pending") {
        $row = $this->getModuleRow($package->module_row);
        $api = $this->getApi($row->meta->apiKey);

        if (!$row) {
            $this->Input->setErrors(array('module_row' => array('missing' => Language::_("ngcs.!error.module_row.missing", true))));
            return;
        }
        $ip_address = null;
        $servername = isset($vars['servername'])? $vars['servername'] : 'Blesta CloudServer cID:'.$vars['client_id'];
        $serverdesc = $vars['clientdescription'];
        $serversize = array('fixed_instance_size_id'=>$package->meta->meta['size']);
        $serverpass = isset($vars['serverpassword'])? $vars['serverpassword'] : '';
        $serverimage = $vars['configoptions']['images'];

        Loader::loadModels($this, array("Clients"));
        if (isset($vars['client_id']) && ($client = $this->Clients->get($vars['client_id'], false)))
            $client_id_code = $client->id_code;

                //die(var_dump($package));
                $params = $this->getFieldsFromInput((array) $vars, $package);
                // $api->ngcs->Server()->create(ServerNAME, HARDWAREConfig, ServerIMG, Description, Password)
                $result = $api->ngcs->Server()->create( uniqid($servername . "_"), $serversize , $serverimage , $serverdesc, $serverpass  );
                $cloud = $result['body'];
                if (isset($result['code']) && $result['code']==202) {
                    $this->log("Create New CloudServer {cloud->id} - {$cloud->name}", serialize("Server_Create"), "input", true);
                    $client_did = $cloud->id;
                    $client_dname = $cloud->name;
                    $client_pass = $cloud->first_password;
                } else {
                    $fa = array(
                        0 => array(
                            "result" => $cloud->message
                        )
                    );
                    $this->Input->setErrors($fa);
                }


            if ($this->Input->errors())
                return;

        return array(
            array(
                'key' => "ngcs_cloud_id",
                'value' => isset($client_did) ? $client_did : null,
                'encrypted' => 0
            ),
            array(
                'key' => "ngcs_cloud_name",
                'value' => isset($client_dname) ? $client_dname : null,
                'encrypted' => 0
            ),
            array(
                'key' => "ngcs_cloud_password",
                'value' => isset($serverpass) ? $serverpass : $client_pass,
                'encrypted' => 1
            )
        );
    }

    private function getFieldsFromInput(array $vars, $package) {
        //die(var_dump($vars));
        $fields = array(
            'name' => isset($vars['servername']) ? $vars['servername'] : null,
            'description' =>isset($vars['clientdescription'])? $vars['clientdescription'] : null,
            'image'=>isset($vars['package_options'][1]->selected_value_name)?$vars['package_options'][1]->selected_value_name : null,
        );
        return $fields;
    }

    public function editService($package, $service, array $vars = null, $parent_package = null, $parent_service = null) {
        $row = $this->getModuleRow($package->module_row);
        $api = $this->getApi($row->meta->apiKey);
        $service_fields = $this->serviceFieldsToObject($service->fields);



        if ($this->Input->errors())
            return;


        return array(
            array(
                'key' => "ngcs_cloud_id",
                'value' => isset($service_fields->ngcs_cloud_id) ? $service_fields->ngcs_cloud_id : null,
                'encrypted' => 0
            ),
            array(
                'key' => "ngcs_cloud_name",
                'value' => isset($service_fields->ngcs_cloud_name) ? $service_fields->ngcs_cloud_name : null,
                'encrypted' => 0
            ),
            array(
                'key' => "ngcs_cloud_password",
                'value' => isset($service_fields->ngcs_cloud_password) ? $service_fields->ngcs_cloud_password : null,
                'encrypted' => 1
            )
        );
    }

    public function suspendService($package, $service, $parent_package = null, $parent_service = null) {
        $row = $this->getModuleRow($package->module_row);
        $api = $this->getApi($row->meta->apiKey);
        $service_fields = $this->serviceFieldsToObject($service->fields);


        if ($row) {
            $service = array();
            $service['type'] = "shutdown";
            $results = $api->ngcs->Server()->powerOffServer($service_fields->ngcs_cloud_id);

            if (isset($results->message) && !empty($results->message)) {
                $fa = array(
                    0 => array(
                        "result" => $results->message
                    )
                );
                $this->Input->setErrors($fa[0]);
            }
        }



        return null;
    }

    public function cancelService($package, $service, $parent_package = null, $parent_service = null) {
        $row = $this->getModuleRow($package->module_row);
        $api = $this->getApi($row->meta->apiKey);
        $service_fields = $this->serviceFieldsToObject($service->fields);


        if ($row) {
            $results = $api->getDeleteResults("droplets/{$service_fields->droplet_id}");
            $api->getDeleteResults("account/keys/{$service_fields->client_sshkey}");
        }

        return null;
    }

    public function validateConnection($apiKey) {
        $api = $this->getApi($apiKey);
        return $api->makeTestConnection();
    }

    private function getApi($apiKey) {
        Loader::load(dirname(__FILE__) . DS . "apis" . DS . "ngcs_api.php");

        $api = new ngcsApi($apiKey);

        return $api;
    }

    private function getRowRules(&$vars) {
        $rules = array(
            'name' => array(
                'valid' => array(
                    'rule' => "isEmpty",
                    'negate' => true,
                    'message' => Language::_("ngcs.error.row.name", true)
                )
            ),
            'apiKey' => array(
                'valid' => array(
                    'rule' => "isEmpty",
                    'negate' => true,
                    'message' => Language::_("ngcs.error.row.apiK_key", true)
                )
            ),
            'apiKey' => array(
                'valid_connection' => array(
                    'rule' => array(array($this, "validateConnection")),
                    'message' => Language::_("ngcs.error.row.connection", true)
                )
            )
        );

        return $rules;
    }

}
