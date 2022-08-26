<?php 

namespace App\Mta\BusTime;

class BusTime {

    private $api_key = "";

    public function __construct(){

        $this->setUp();

    }


    private function setUp(){
        
        $this->setApiKey();
        
    }

    private function setApiKey(){

        $this->api_key = config("mta.bustime.credentials.developer_key");

    }

}

?>