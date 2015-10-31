<?php

require 'vendor/autoload.php';

use NGCSv1\Adapter\HttpAdapter;
use NGCSv1\NGCSv1;

class ngcsApi
{
    private $apiKey = "";
    public $ngcs;
    public $adapter;

    public function __construct($api_key) {
        $this->apiKey = $api_key;
        $this->adapter = new HttpAdapter($this->apiKey);
        $this->ngcs = new NGCSv1($this->adapter);
    }

    public function makeTestConnection() {
        try{
            $this->ngcs->Server()->getAll();
            return true;
        }catch(\Exception $ex){
            return false;
        }
    }
}
