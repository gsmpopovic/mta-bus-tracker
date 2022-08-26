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
        Schema::create('journeys', function (Blueprint $table) {
            $table->id();
            $table->string("line_ref")->nullable();
            $table->string("direction_ref")->nullable();
            $table->string("journey_pattern_ref")->nullable();
            $table->string("published_line_name")->nullable();
            $table->string("operator_ref")->nullable();
            $table->string("destination_name")->nullable();
           $table->string( "origin_aimed_departure_time")->nullable();
            $table->boolean("monitored")->default(false);
           $table->string("vehicle_longitude")->nullable();
           $table->string("vehicle_latitude")->nullable();
            $table->string("bearing")->nullable();
           $table->string("progress_rate")->nullable();
            $table->string("progress_status")->nullable();
           $table->string("block_ref")->nullable();
            $table->string("vehicle_ref")->nullable();
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
        Schema::dropIfExists('journeys');
    }
};
