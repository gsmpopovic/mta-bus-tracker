<?php 

namespace App\Mta\BusTime;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;


class BusTime {

    private $api_key = "";
    private $response = [];
    private $response_body = [];

    public function __construct(){

        $this->setUp();

    }


    private function setUp(){
        
        $this->setApiKey();

    }

    private function setApiKey(){

        $this->api_key = config("mta.bustime.credentials.developer_key");

    }

    public function monitorVehicles(){

        $client = new Client();
        
        $url = $this->siriVehicleMonitoringUrl();

        $request = new Request('GET', $url);
        
        $query_params = $this->queryParams();

        try {

            $response = $client->send($request, $query_params);

            $this->captureResponse($response);

        } catch(\GuzzleHttp\Exception\ClientException $e) {

            echo (string) $e->getResponse();

        }

    }

    public function captureResponse($response = new \Psr\Http\Message\ResponseInterface()){

        if(isset($response) && ($response->getBody() !== null)){

            $this->response = $response; 

            $this->response_body = json_decode($response->getBody(), true);
        
        }

    }

    public function siriVehicleMonitoringUrl(){

        $url = config("mta.bustime.api.siri_base_uri") . config("mta.bustime.api.siri_endpoints.vehicle_monitoring");

        return $url; 

    }

    public function queryParams($params = []){

        $query_params = [];
        $query_params['query'] = []; 

        if(isset($params) && !empty($params)){

            foreach($params as $k => $v){

                $query_params['query'][$k] = $v;         
            
            }
        
        }

        $query_params['query']['key'] = $this->api_key;

        return $query_params; 

    }

    public function monitorStops(){

    }

}

?>