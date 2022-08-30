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
        Schema::create('mta_bus_route_mta_bus_stop', function (Blueprint $table) {
            $table->id();
            $table->foreignId("mta_bus_stop_id")->references("id")->on("mta_bus_stops");
            $table->foreignId("mta_bus_route_id")->references("id")->on("mta_bus_routes");
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
        Schema::dropIfExists('mta_bus_route_mta_bus_stop');
    }
};
