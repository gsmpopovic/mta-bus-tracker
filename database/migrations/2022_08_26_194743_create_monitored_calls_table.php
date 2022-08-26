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
        "aimed_arrival_time": "2022-08-25T20:17:08.000-04:00",
        "expected_arrival_time": "2022-08-25T20:15:09.417-04:00",
        "aimed_departure_time": "2022-08-25T20:17:08.000-04:00",
        "expected_departure_time": "2022-08-25T20:15:09.417-04:00",

        "presentable_distance": "< 1 stop away",
        "distance_from_call": 363.22,
        "stsop_from_call": 0,
        "call_distance_along_route": 366.08

        "stop_point_ref": "MTA_550035",
        "visit_number": 1,
        "stop_point_name": "JAMAICA AV / 164 ST"
         */

        Schema::create('monitored_calls', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId("journey_id")->references("id")->on("journeys");
            $table->string("vehicle_ref")->nullable();
            $table->string("aimed_arrival_time")->nullable();
            $table->string("expected_arrival_time")->nullable();
            $table->string("aimed_departure_time")->nullable();
            $table->string("expected_departure_time")->nullable();

            $table->string("presentable_distance")->nullable();
            $table->float("distance_from_call")->nullable();
            $table->float("stops_from_call")->nullable();
            $table->float("call_distance_along_route")->nullable();

            $table->string("stop_point_ref")->nullable();
            $table->string("visit_number")->nullable();
            $table->string("stop_point_name")->nullable();
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
        Schema::dropIfExists('monitored_calls');
    }
};
