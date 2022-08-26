<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
                /*
                            "MonitoredVehicleJourney": {
                                "LineRef": "MTA NYCT_B63",
                                "DirectionRef": "1",
                                "FramedVehicleJourneyRef": {
                                    "DataFrameRef": "2022-08-25",
                                    "DatedVehicleJourneyRef": "MTA NYCT_JG_C2-Weekday-128100_B63_681"
                                },
                                "JourneyPatternRef": "MTA_B630011",
                                "PublishedLineName": "B63",
                                "OperatorRef": "MTA NYCT",
                                "OriginRef": "MTA_901601",
                                "DestinationName": "BAY RIDGE SHORE RD via 5 AV",
                                "OriginAimedDepartureTime": "2022-08-25T21:21:00.000-04:00",
                                "SituationRef": [
                                    {
                                        "SituationSimpleRef": "MTA NYCT_lmm:planned_work:4268"
                                    }
                                ],
                                "Monitored": true,
                                "VehicleLocation": {
                                    "Longitude": -74.012419,
                                    "Latitude": 40.643064
                                },
                                "Bearing": 44.00653,
                                "ProgressRate": "normalProgress",
                                "ProgressStatus": "prevTrip",
                                "BlockRef": "MTA NYCT_JG_C2-Weekday_C_JG_22800_B63-660",
                                "VehicleRef": "MTA NYCT_414",
                                "MonitoredCall": {
                                    "AimedArrivalTime": "2022-08-25T21:39:58.000-04:00",
                                    "AimedDepartureTime": "2022-08-25T21:39:58.000-04:00",
                                    "Extensions": {
                                        "Distances": {
                                            "PresentableDistance": "7.0 miles away",
                                            "DistanceFromCall": 11189.2,
                                            "StopsFromCall": 14,
                                            "CallDistanceAlongRoute": 3328.41
                                        }
                                    },
                                    "StopPointRef": "MTA_308214",
                                    "VisitNumber": 1,
                                    "StopPointName": "5 AV/UNION ST"
                                },
                                "OnwardCalls": {}
                            },
        */

        Schema::create('lines', function (Blueprint $table) {
            $table->id();
            $table->string("line_ref")->null(); //"LineRef": "MTA NYCT_B63",
            $table->string("published_line_name")->null(); //"PublishedLineName": "B63",
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lines');
    }
};