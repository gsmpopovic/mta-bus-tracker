<?php 

namespace App\Mta\BusTime;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use \Psr\Http\Message\ResponseInterface;
use \GuzzleHttp\Exception\ClientException;


class BusTime {

    private $api_key = "";
    public $response = [];
    public $response_body = [];

    public function __construct(){

        $this->setUp();

    }


    private function setUp(){
        
        $this->setApiKey();

    }

    private function setApiKey(){

        $this->api_key = config("mta.bustime.credentials.developer_key");

    }

    public function captureResponse(ResponseInterface $response){

        if(isset($response) && ($response->getBody() !== null)){

            $this->response = $response; 

            $this->response_body = json_decode($response->getBody(), true);
        
        }

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

        $client = new Client();
        
        $url = $this->getSiriStopMonitoringUrl();

        $request = new Request('GET', $url);
        
        $query_params = $this->queryParams();

        try {

            $response = $client->send($request, $query_params);

        } catch(ClientException $e) {

            echo (string) $e->getResponse();

        } finally {

            $this->captureResponse($response);

        }

    }

    public function monitorVehicles(){

        $client = new Client();
        
        $url = $this->getSiriVehicleMonitoringUrl();

        $request = new Request('GET', $url);
        
        $query_params = $this->queryParams();

        try {

            $response = $client->send($request, $query_params);

            $this->captureResponse($response);

        } catch(ClientException $e) {

            echo (string) $e->getResponse();

        }

    }

    public function getSiriVehicleMonitoringUrl(){

        $url = config("mta.bustime.api.siri_base_uri") . config("mta.bustime.api.siri_endpoints.vehicle_monitoring");

        return $url; 

    }

    public function getSiriStopMonitoringUrl(){

        $url = config("mta.bustime.api.siri_base_uri") . config("mta.bustime.api.siri_endpoints.stop_monitoring");

        return $url; 

    }

    public static function getSiriVehicleMonitoringActivityJson(){

        try {

            $path = resource_path() . DIRECTORY_SEPARATOR . "json" . DIRECTORY_SEPARATOR . "siri-vehicle-monitoring.json";
        
            $vehicle_monitoring_json = file_get_contents($path);
            
            $vehicle_monitoring_array = json_decode($vehicle_monitoring_json, true);
    
            $vehicle_activity = $vehicle_monitoring_array["Siri"]["ServiceDelivery"]["VehicleMonitoringDelivery"][0]["VehicleActivity"];
    
            return $vehicle_activity;

        } catch (\Exception $e){

            echo $e->getMessage();

        }
    
    }

public static function getUniqueLines(){

    $vehicle_activity = self::getSiriVehicleMonitoringActivityJson();

    $lines = [];

    foreach($vehicle_activity as $activity){

        $monitored_vehicle_journey = $activity["MonitoredVehicleJourney"];

        $lines[$monitored_vehicle_journey["PublishedLineName"]] = $monitored_vehicle_journey["LineRef"];

    }

    return $lines; 

}

public static function getUniqueStops(){

    $vehicle_activity = self::getSiriVehicleMonitoringActivityJson();

    $stops = [];

    foreach($vehicle_activity as $activity){

        $stop = [];

        $monitored_vehicle_journey = $activity["MonitoredVehicleJourney"];

        
        if(!isset($monitored_vehicle_journey["MonitoredCall"])){
            continue;
            //return $monitored_vehicle_journey;
        }

        $monitored_call = $monitored_vehicle_journey["MonitoredCall"];

        //$prefixes = ["MTA NYCT_", "MTA_", "MTA_NYCT", "MTABC_", "NYCT_"];

        $stop["VisitNumber"] = (string) $monitored_call["VisitNumber"];
        $stop["StopPointName"] = (string) $monitored_call["StopPointName"];
        $stop["MonitoringRef"] = (string) str_replace($monitored_vehicle_journey["OperatorRef"] . "_", "", $monitored_call["StopPointRef"]);

        $stop["LineRef"] = (string) $monitored_vehicle_journey["LineRef"];

        //$stop["PublishedLineName"] = $monitored_vehicle_journey["PublishedLineName"];
        $stop["OperatorRef"] = (string) $monitored_vehicle_journey["OperatorRef"];

        $stops[$monitored_call["StopPointRef"]] = $stop;

    }

    return $stops; 

}

}

?>