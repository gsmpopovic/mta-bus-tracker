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
        Schema::create('mta_bus_stops', function (Blueprint $table) {
            $table->id();
            $table->string("code")->nullable(); // "100304",
            $table->string("direction")->nullable(); // "W",
            $table->string("id")->nullable(); // "MTA_100304",
            $table->string("lat")->nullable(); // 40.857836,
            $table->string("locationType")->nullable(); // 0,
            $table->string("lon")->nullable(); // -73.844305,
            $table->string("name")->nullable(); // "PELHAM PKWY/EASTCHESTER RD",
            $table->string("wheelchairBoarding")->nullable(); // "UNKNOWN"
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
        Schema::dropIfExists('mta_bus_stops');
    }
};
