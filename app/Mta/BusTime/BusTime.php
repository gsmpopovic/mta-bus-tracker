<?php

namespace App\Mta\BusTime;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use \GuzzleHttp\Exception\ClientException;
use \Psr\Http\Message\ResponseInterface;

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

    public function captureResponse(ResponseInterface $response)
    {

        if (isset($response) && ($response->getBody() !== null)) {

            $this->response = $response;

            $this->response_body = json_decode($response->getBody(), true);

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

        } catch (ClientException $e) {

            echo (string) $e->getResponse();

        }

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

}
