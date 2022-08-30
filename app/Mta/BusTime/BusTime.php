<?php

namespace App\Mta\BusTime;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Log;
use \GuzzleHttp\Exception\ClientException;

class BusTime
{

    private $api_key = "";
    public $response = [];
    public $response_body = [];

    public function __construct()
    {

        $this->setUp();

    }

    private function setUp()
    {

        $this->setApiKey();

    }

    private function setApiKey()
    {

        $this->api_key = config("mta.bustime.credentials.developer_key");

    }

    public function captureResponse($response)
    {

        if (isset($response) && ($response->getBody() !== null)) {

            $this->response = $response;

            $headers = $this->response->getHeaders();

            if($headers["Content-Type"][0] =="application/xml;charset=ISO-8859-1"){

                $xml = simplexml_load_string($this->response->getBody(),'SimpleXMLElement',LIBXML_NOCDATA);
                $this->response_body = json_decode(json_encode($xml), true);
            }
            else {
                $this->response_body = json_decode($response->getBody(), true);

            }
        }

    }

    public function queryParams($params = [])
    {

        $query_params = [];
        $query_params['query'] = [];

        if (isset($params) && !empty($params)) {

            foreach ($params as $k => $v) {

                $query_params['query'][$k] = $v;

            }

        }

        $query_params['query']['key'] = $this->api_key;

        return $query_params;

    }

    public function monitorStops()
    {

        $client = new Client();

        $url = $this->getSiriStopMonitoringUrl();

        $request = new Request('GET', $url);

        $query_params = $this->queryParams();

        try {

            $response = $client->send($request, $query_params);

        } catch (ClientException $e) {

            echo (string) $e->getResponse();

        } finally {

            $this->captureResponse($response);

        }

    }

    public function monitorVehicles()
    {

        $client = new Client();

        $url = $this->getSiriVehicleMonitoringUrl();

        $request = new Request('GET', $url);

        $query_params = $this->queryParams();

        try {

            $response = $client->send($request, $query_params);

            $this->captureResponse($response);

            $this->updateVehiclePositions();

        } catch (ClientException $e) {

            echo (string) $e->getResponse();

        }

    }

    public function seedObaMtaRoutes(){

        $routes = $this->processObaMtaRoutes();

        foreach($routes as $route){

            $params = [];

            // 245 => array:8 [
            //     "id" => "MTA NYCT_B38"
            //     "shortName" => "B38"
            //     "longName" => "Ridgewood - Downtown Brooklyn"
            //     "description" => "via DeKalb & Lafayette Av"
            //     "type" => "3"
            //     "color" => "00AEEF"
            //     "textColor" => "FFFFFF"
            //     "agencyId" => "MTA NYCT"
            //   ]

            $params["line_ref"] = $route["id"];
            $params["short_name"] = $route["shortName"];
            $params["long_name"] = $route["longName"];
            $params["description"] = $route["description"];
            $params["type"] = $route["type"];
            $params["color"] = $route["color"];
            $params["text_color"] = $route["textColor"];
            $params["agency_id"] = $route["agencyId"];

            \App\Models\MtaBusRoute::create($params);


        }

    }

    public function processObaMtaRoutes(){

        $this->getObaMtaRoutes();

        /*
        [
        "version" => "2",
        "code" => "200",
        "currentTime" => "1661705838092",
        "text" => "OK",
        "data" => [
        "@attributes" => [
            "class" => "listWithReferences",
        ],
        "references" => [
            "agencies" => [
            "agency" => [
                "id" => "MTA NYCT",
                "name" => "MTA New York City Transit",
                "url" => "http://www.mta.info",
                "timezone" => "America/New_York",
                "lang" => "en",
                "phone" => "718-330-1234",
                "privateService" => "false",
            ],
            ],
        ],
        "list" => [
            "route" => [
        */

        $body = $this->response_body;

        $routes = $body["data"]["list"]["route"];

        /*

                245 => array:8 [
          "id" => "MTA NYCT_B38"
          "shortName" => "B38"
          "longName" => "Ridgewood - Downtown Brooklyn"
          "description" => "via DeKalb & Lafayette Av"
          "type" => "3"
          "color" => "00AEEF"
          "textColor" => "FFFFFF"
          "agencyId" => "MTA NYCT"
        ]

        */

        return $routes;


    }

    public function getObaMtaRoutes(){

        $client = new Client();

        $url = $this->getObaMtaRoutesUrl();

        $request = new Request('GET', $url);

        $query_params = $this->queryParams();

        try {

            $response = $client->send($request, $query_params);

            $this->captureResponse($response);

        } catch (ClientException $e) {

            Log::debug(json_encode($e));

        }

    }

    public function getObaMtaStopsForRoute($route, $params = []){

        $client = new Client();

        $url = $this->getObaMtaStopsForRouteUrl($route);

        $request = new Request('GET', $url);

        if(empty($params)){

            $params = [
                "includePolylines"=>false,
                "version"=>2
            ];
        
        }

        $query_params = $this->queryParams($params);

        try {

            $response = $client->send($request, $query_params);

            $this->captureResponse($response);

            
        } catch (ClientException $e) {

            echo $e->getMessage();

        }

    }

    public function getObaMtaRoutesUrl()
    {

        $url = config("mta.bustime.api.oba_base_uri") . config("mta.bustime.api.oba_endpoints.mta_routes");

        return $url;

    }

    public function getObaMtaStopsForRouteUrl($route)
    {

        $url = config("mta.bustime.api.oba_base_uri") . config("mta.bustime.api.oba_endpoints.stops_for_route") . $route . ".json";

        return $url;

    }

    public function getSiriVehicleMonitoringUrl()
    {

        $url = config("mta.bustime.api.siri_base_uri") . config("mta.bustime.api.siri_endpoints.vehicle_monitoring");

        return $url;

    }

    public function getSiriStopMonitoringUrl()
    {

        $url = config("mta.bustime.api.siri_base_uri") . config("mta.bustime.api.siri_endpoints.stop_monitoring");

        return $url;

    }

    /* Seeding functions */

    public static function getSiriVehicleMonitoringActivityJsonSeed()
    {

        try {

            $path = resource_path() . DIRECTORY_SEPARATOR . "json" . DIRECTORY_SEPARATOR . "siri-vehicle-monitoring.json";

            $vehicle_monitoring_json = file_get_contents($path);

            $vehicle_monitoring_array = json_decode($vehicle_monitoring_json, true);

            $vehicle_activity = $vehicle_monitoring_array["Siri"]["ServiceDelivery"]["VehicleMonitoringDelivery"][0]["VehicleActivity"];

            return $vehicle_activity;

        } catch (\Exception$e) {

            echo "\n" . $e->getMessage() . "\n";

        }

    }

    public static function getUniqueLinesSeed()
    {

        $vehicle_activity = self::getSiriVehicleMonitoringActivityJsonSeed();

        $lines = [];

        foreach ($vehicle_activity as $activity) {

            $monitored_vehicle_journey = $activity["MonitoredVehicleJourney"];

            $lines[$monitored_vehicle_journey["PublishedLineName"]] = $monitored_vehicle_journey["LineRef"];

        }

        return $lines;

    }

    public static function getUniqueStopsSeed()
    {

        $vehicle_activity = self::getSiriVehicleMonitoringActivityJsonSeed();

        $stops = [];

        foreach ($vehicle_activity as $activity) {

            $stop = [];

            $monitored_vehicle_journey = $activity["MonitoredVehicleJourney"];

            if (!isset($monitored_vehicle_journey["MonitoredCall"])) {
                continue;
            }

            $monitored_call = $monitored_vehicle_journey["MonitoredCall"];

            //$prefixes = ["MTA NYCT_", "MTA_", "MTA_NYCT", "MTABC_", "NYCT_"];

            $stop["VisitNumber"] = (string) $monitored_call["VisitNumber"];
            $stop["StopPointName"] = (string) $monitored_call["StopPointName"];
            $stop["MonitoringRef"] = (string) str_replace($monitored_vehicle_journey["OperatorRef"] . "_", "", $monitored_call["StopPointRef"]);
            $stop["LineRef"] = (string) $monitored_vehicle_journey["LineRef"];
            $stop["OperatorRef"] = (string) $monitored_vehicle_journey["OperatorRef"];

            $stops[$monitored_call["StopPointRef"]] = $stop;

        }

        return $stops;

    }

    public static function getMonitoredCallsSeed()
    {

        $vehicle_activity = self::getSiriVehicleMonitoringActivityJsonSeed();

        $monitored_calls = [];

        foreach ($vehicle_activity as $activity) {

            $monitored_vehicle_journey = $activity["MonitoredVehicleJourney"];

            if (!isset($monitored_vehicle_journey["MonitoredCall"])) {
                continue;
                //return $monitored_vehicle_journey;
            }

            $mc = $monitored_vehicle_journey["MonitoredCall"];

            $monitored_call = [];

            $monitored_call["vehicle_ref"] = $monitored_vehicle_journey["VehicleRef"] ?? null;
            $monitored_call["aimed_arrival_time"] = $mc["AimedArrivalTime"] ?? null;
            $monitored_call["expected_arrival_time"] = $mc["ExpectedArrivalTime"] ?? null;
            $monitored_call["aimed_departure_time"] = $mc["AimedDepartureTime"] ?? null;
            $monitored_call["expected_departure_time"] = $mc["ExpectedDepartureTime"] ?? null;

            $distances = $mc["Extensions"]["Distances"];

            $monitored_call["presentable_distance"] = $distances["PresentableDistance"] ?? null;
            $monitored_call["distance_from_call"] = $distances["DistanceFromCall"] ?? null;
            $monitored_call["stops_from_call"] = $distances["StopsFromCall"] ?? null;
            $monitored_call["call_distance_along_route"] = $distances["CallDistanceAlongRoute"] ?? null;
            $monitored_call["stop_point_ref"] = $mc["StopPointRef"] ?? null;
            $monitored_call["visit_number"] = $mc["VisitNumber"] ?? null;
            $monitored_call["stop_point_name"] = $mc["StopPointName"] ?? null;

            $monitored_calls[] = $monitored_call;

        }

        return $monitored_calls;

    }

    public static function getUniqueBusesSeed()
    {

        $vehicle_activity = self::getSiriVehicleMonitoringActivityJsonSeed();

        $buses = [];

        foreach ($vehicle_activity as $activity) {

            $bus = [];

            $monitored_vehicle_journey = $activity["MonitoredVehicleJourney"];

        }

        return $buses;

    }

    public static function getJourneysSeed()
    {

        $vehicle_activity = self::getSiriVehicleMonitoringActivityJsonSeed();

        $journeys = [];

        foreach ($vehicle_activity as $activity) {

            $monitored_vehicle_journey = $activity["MonitoredVehicleJourney"] ?? '';

            if (isset($monitored_vehicle_journey["Monitored"]) && $monitored_vehicle_journey["Monitored"] == true) {

                $journey = [];

                $journey["line_ref"] = $monitored_vehicle_journey["LineRef"] ?? '';
                $journey["direction_ref"] = $monitored_vehicle_journey["DirectionRef"] ?? '';
                $journey["journey_pattern_ref"] = $monitored_vehicle_journey["JourneyPatternRef"] ?? '';
                $journey["published_line_name"] = $monitored_vehicle_journey["PublishedLineName"] ?? '';
                $journey["operator_ref"] = $monitored_vehicle_journey["OperatorRef"] ?? '';
                $journey["destination_name"] = $monitored_vehicle_journey["DestinationName"] ?? '';
                $journey["origin_aimed_departure_time"] = $monitored_vehicle_journey["OriginAimedDepartureTime"] ?? '';
                $journey["vehicle_longitude"] = $monitored_vehicle_journey["VehicleLocation"]["Longitude"] ?? '';
                $journey["vehicle_latitude"] = $monitored_vehicle_journey["VehicleLocation"]["Latitude"] ?? '';
                $journey["bearing"] = $monitored_vehicle_journey["Bearing"] ?? '';
                $journey["progress_rate"] = $monitored_vehicle_journey["ProgressRate"] ?? '';
                $journey["progress_status"] = $monitored_vehicle_journey["ProgressStatus"] ?? '';
                $journey["block_ref"] = $monitored_vehicle_journey["BlockRef"] ?? '';
                $journey["vehicle_ref"] = $monitored_vehicle_journey["VehicleRef"] ?? '';
                $journey["monitored"] = $monitored_vehicle_journey["Monitored"] ?? false;

                $journeys[] = $journey;

            } else {

//

            }

        }

        return $journeys;
    }

    /* Seeding functions */

    public function updateVehiclePositions()
    {

        //\DB::Connection()->disableQueryLog();

        //$journeys = \App\Models\Journey::all()->with("monitored_calls")->getDictionary();

        $journeys = \App\Models\Journey::with("monitored_calls")->get()->keyBy('vehicle_ref');

        Log::debug("Start updating vehicle positions for " . count($journeys) . " journeys");

        //Log::debug(json_encode($this->response_body));

        $vehicle_activity = $this->response_body["Siri"]["ServiceDelivery"]["VehicleMonitoringDelivery"][0]["VehicleActivity"];

        foreach ($vehicle_activity as $activity) {

            $monitored_vehicle_journey = $activity["MonitoredVehicleJourney"];

            try {
                if (isset($journeys[$monitored_vehicle_journey["VehicleRef"]])) {

                    $journey = $journeys[$monitored_vehicle_journey["VehicleRef"]];

                   // Log::debug("\nUpdating Journey for #" . $journey->id);

                    //$journey->line_ref"] = $monitored_vehicle_journey["LineRef"] ?? '';
                    $journey->direction_ref = $monitored_vehicle_journey["DirectionRef"] ?? $journey->direction_ref;
                    $journey->journey_pattern_ref = $monitored_vehicle_journey["JourneyPatternRef"] ?? $journey->journey_pattern_ref;
                    //$journey->published_line_name"] = $monitored_vehicle_journey["PublishedLineName"] ?? '';
                    //$journey->operator_ref"] = $monitored_vehicle_journey["OperatorRef"] ?? '';
                    $journey->destination_name = $monitored_vehicle_journey["DestinationName"] ?? $journey->destination_name;
                    $journey->origin_aimed_departure_time = $monitored_vehicle_journey["OriginAimedDepartureTime"] ?? $journey->origin_aimed_departure_time;
                    $journey->vehicle_longitude = $monitored_vehicle_journey["VehicleLocation"]["Longitude"] ?? $journey->vehicle_longitude;
                    $journey->vehicle_latitude = $monitored_vehicle_journey["VehicleLocation"]["Latitude"] ?? $journey->vehicle_latitude;
                    $journey->bearing = $monitored_vehicle_journey["Bearing"] ?? $journey->bearing;
                    $journey->progress_rate = $monitored_vehicle_journey["ProgressRate"] ?? $journey->progress_rate;
                    $journey->progress_status = $monitored_vehicle_journey["ProgressStatus"] ?? $journey->progress_status;
                    $journey->block_ref = $monitored_vehicle_journey["BlockRef"] ?? $journey->block_ref;
                    //$journey->vehicle_ref = $monitored_vehicle_journey["VehicleRef"] ?? '';
                    $journey->monitored = $monitored_vehicle_journey["Monitored"] ?? $journey->monitored;

                    $journey->save();

                    if (isset($monitored_vehicle_journey["MonitoredCall"])) {
                        $mc = $monitored_vehicle_journey["MonitoredCall"];

                        if(isset($journey->monitored_call) && is_object($journey->monitored_call)) {

                            $monitored_call = $journey->monitored_call;

                           // Log::debug("\nUpdating MC for " . $journey->vehicle_ref);

                            $monitored_call->vehicle_ref = $monitored_vehicle_journey["VehicleRef"] ?? $monitored_call->vehicle_ref;
                            $monitored_call->aimed_arrival_time = $mc["AimedArrivalTime"] ?? $monitored_call->aimed_arrival_time;
                            $monitored_call->expected_arrival_time = $mc["ExpectedArrivalTime"] ?? $monitored_call->expected_arrival_time;
                            $monitored_call->aimed_departure_time = $mc["AimedDepartureTime"] ?? $monitored_call->aimed_departure_time;
                            $monitored_call->expected_departure_time = $mc["ExpectedDepartureTime"] ?? $monitored_call->expected_departure_time;

                            $distances = $mc["Extensions"]["Distances"];

                            $monitored_call->presentable_distance = $distances["PresentableDistance"] ?? $monitored_call->presentable_distance;
                            $monitored_call->distance_from_call = $distances["DistanceFromCall"] ?? $monitored_call->distance_from_call;
                            $monitored_call->stops_from_call = $distances["StopsFromCall"] ?? $monitored_call->stops_from_call;
                            $monitored_call->call_distance_along_route = $distances["CallDistanceAlongRoute"] ?? $monitored_call->call_distance_along_route;
                            $monitored_call->stop_point_ref = $mc["StopPointRef"] ?? $monitored_call->stop_point_ref;
                            $monitored_call->visit_number = $mc["VisitNumber"] ?? $monitored_call->visit_number;
                            $monitored_call->stop_point_name = $mc["StopPointName"] ?? $monitored_call->stop_point_name;

                            $monitored_call->save();

                        }

                    }

                }
                
            } catch (\Exception$e) {
                Log::debug("\ERROR UPDATING JOURNEY" . $e . "\n");

            } 

        }

        Log::debug("Stop updating vehicle positions for " . count($journeys) . " journeys");
    }

}
