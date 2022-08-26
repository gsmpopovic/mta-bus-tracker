<?php 


return [

    // https://bustime.mta.info/wiki/Developers/SIRIIntro

    "siri_base_uri" => "http://bustime.mta.info/api/siri/",

    "siri_endpoints" => [
        "vehicle_monitoring" => "vehicle-monitoring.json",
        "stop_monitoring" => "stop-monitoring.json",

    ],

    // https://bustime.mta.info/wiki/Developers/OneBusAwayRESTfulAPI
    // Incomplete. 

    "oba_base_uri" => " http://bustime.mta.info/api/where/",

    "oba_endpoints" => [
        "agencies" => "agencies-with-coverage.xml",
        "mta_routes" => "routes-for-agency/MTA%20NYCT.xml",
        "stop" => "stop/" , // stop/MTA_STOP-ID.xml
        "stops_for_route/" => "stops-for-route/MTA%20NYCT_", //stops-for-route/MTA%20NYCT_M1.json?key=YOUR_KEY_HERE&includePolylines=false&version=2
        "stops_near_location" => "stops-for-location.json" // ?lat=40.748433&lon=-73.985656&latSpan=0.005&lonSpan=0.005&key=YOUR_KEY_HERE


    ]

];

    ?>