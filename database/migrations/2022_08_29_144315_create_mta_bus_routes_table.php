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
        Schema::create('mta_bus_routes', function (Blueprint $table) {
            $table->id();
            $table->string("line_ref")->nullable();

            $table->string("short_name")->nullable();
            $table->string("long_name")->nullable();

            $table->string("description")->nullable();
            $table->string("type")->nullable();
            $table->string("color")->nullable();
            $table->string("text_color")->nullable();
            $table->string("agency_id")->nullable();

            $table->timestamps();

            /* 
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
            */
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('routes');
    }
};
