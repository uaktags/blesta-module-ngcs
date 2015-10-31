<?php

/*
 * This file is part of the NGCSv1 library.
 *
 * (c) Tim Garrity <timgarrity89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NGCSv1\Api;

use NGCSv1\Entity\Action as ActionEntity;
use NGCSv1\Entity\Image as ImageEntity;
use NGCSv1\Entity\MonitorPolicy;

/**
 * @author Tim Garrity <timgarrity89@gmail.com>
 */
class MonitoringPolicy extends AbstractApi
{
    /**
     * @param array $criteria
     *
     * @return ImageEntity[]
     */
    public function getAll()
    {
        $monitors = $this->adapter->get(sprintf('%s/monitoring_policies', self::ENDPOINT));

        return array_map(function ($monitor) {
            return new MonitorPolicy($monitor);
        }, $monitors);
    }

    /**
     * @param int $id
     *
     * @return ImageEntity
     */
    public function getById($id)
    {
        $monitor = $this->adapter->get(sprintf('%s/monitoring_policies/%s', self::ENDPOINT, $id));

        return new MonitorPolicy($monitor);
    }

    public function create($serverID)
    {
        return 0;
        //return $this->adapter->post(sprintf('%s/monitoring_policies', self::ENDPOINT), $data);
    }

    public function delete($id)
    {
        return $this->adapter->delete(sprintf('%s/monitoring_policies/%s', self::ENDPOINT, $id));
    }

    public function modifyName($id, $name)
    {
        return $this->adapter->put(sprintf('%s/monitoring_policies/%s', self::ENDPOINT, $id), array('name'=>$name));
    }

    public function modifyDescription($id, $description)
    {
        return $this->adapter->put(sprintf('%s/monitoring_policies/%s', self::ENDPOINT, $id), array('description'=>$description));
    }

    /**
     * GET Functions
     */

    public function getPortsByID($id)
    {
        return $this->adapter->get(sprintf('%s/monitoring_policies/%s/ports', self::ENDPOINT, $id));
    }

    public function getPortByPortID($id, $port)
    {
        return $this->adapter->get(sprintf('%s/monitoring_policies/%s/ports/%s', self::ENDPOINT, $id, $port));
    }

    public function getProcesses($id)
    {
        return $this->adapter->get(sprintf('%s/monitoring_policies/%s/processes', self::ENDPOINT, $id));
    }

    public function getProcessbyID($id, $pid)
    {
        return $this->adapter->get(sprintf('%s/monitoring_policies/%s/processes/%s', self::ENDPOINT, $id, $pid));
    }

    public function getServers($id)
    {
        return $this->adapter->get(sprintf('%s/monitoring_policies/%s/servers', self::ENDPOINT, $id));
    }

    public function getServerByID($id, $sid)
    {
        return $this->adapter->get(sprintf('%s/monitoring_policies/%s/servers/%s', self::ENDPOINT, $id, $sid));
    }

    /**
     * DELETE Functions
     */

    public function removePort($id, $port)
    {
        return $this->adapter->delete(sprintf('%s/monitoring_policies/%s/ports/%s', self::ENDPOINT, $id, $port));
    }

    public function removeProcess($id, $pid)
    {
        return $this->adapter->delete(sprintf('%s/monitoring_policies/%s/processes/%s', self::ENDPOINT, $id, $pid));
    }

    /**
     * POST Functions
     */

    public function addPorts($id, $proto, $port, $alert, $email)
    {
        return 0;
        //return $this->adapter->post(sprintf('%s/monitoring_policies/%s/ports/%s', self::ENDPOINT, $id));
    }

    public function addProcess($id, $process, $alert, $email)
    {
        return 0;
        //return $this->adapter->post(sprintf('%s/monitoring_policies/%s/processes/%s', self::ENDPOINT, $id));
    }

    public function addServer($id, $servers)
    {
        return 0;
        //return $this->adapter->post(sprintf('%s/monitoring_policies/%s/servers/%s', self::ENDPOINT, $id));
    }

    /**
     * PUT Functions
     */

    public function modifyMonitoring($id, $name, $desc, $email, $thresholds)
    {
        return $this->adapter->put(sprintf('%s/monitoring_policies/%s'));
    }

    public function modifyPort($id, $port, $proto, $alert, $email)
    {
        return $this->adapter->put(sprintf('%s/monitoring_policies/%s'));
    }

    public function modifyProcess($id, $pid, $process, $alert, $email)
    {
        return $this->adapter->put(sprintf('%s/monitoring_policies/%s'));
    }
}
