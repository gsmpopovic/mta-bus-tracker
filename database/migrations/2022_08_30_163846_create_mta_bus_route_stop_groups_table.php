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
                    "stopGroups": [
                        {
                            "id": "1",
                            "name": {
                                "name": "GRAND ST via 5 AV/BROADWAY",
                                "names": [
                                    "GRAND ST via 5 AV/BROADWAY"
                                ],
                                "type": "destination"
                            },
                            "polylines": [],
                            "stopIds": [
                                "MTA_405284",
                                "MTA_400088",
                                "MTA_400089",
                                "MTA_400091",
                                "MTA_400092",
                                "MTA_400093",
                                "MTA_400094",
                                "MTA_400095",
                                "MTA_400096",
                                "MTA_400097",
                                "MTA_400098",
                                "MTA_400099",
                                "MTA_400100",
                                "MTA_400101",
                                "MTA_400102",
                                "MTA_400103",
                                "MTA_400105",
                                "MTA_400107",
                                "MTA_400108",
                                "MTA_400109",
                                "MTA_400110",
                                "MTA_400111",
                                "MTA_404081",
                                "MTA_404168",
                                "MTA_400115",
                                "MTA_400116",
                                "MTA_400117",
                                "MTA_400118",
                                "MTA_400119",
                                "MTA_803162",
                                "MTA_400122",
                                "MTA_400123",
                                "MTA_400124",
                                "MTA_400125",
                                "MTA_400126",
                                "MTA_400127",
                                "MTA_400128",
                                "MTA_400512",
                                "MTA_403266",
                                "MTA_400514",
                                "MTA_400515",
                                "MTA_400516",
                                "MTA_405179",
                                "MTA_400518",
                                "MTA_400519",
                                "MTA_400323",
                                "MTA_400324",
                                "MTA_400325",
                                "MTA_400326",
                                "MTA_400327",
                                "MTA_400329",
                                "MTA_400330",
                                "MTA_400331",
                                "MTA_402857",
                                "MTA_400333",
                                "MTA_400334",
                                "MTA_400335",
                                "MTA_400336",
                                "MTA_400337",
                                "MTA_400153",
                                "MTA_400154",
                                "MTA_400155",
                                "MTA_403311",
                                "MTA_404916",
                                "MTA_400157",
                                "MTA_404010",
                                "MTA_403793",
                                "MTA_803192"
                            ],
                            "subGroups": []
                        },
                        {
                            "id": "0",
                            "name": {
                                "name": "HARLEM 147 ST via MADISON AV",
                                "names": [
                                    "HARLEM 147 ST via MADISON AV"
                                ],
                                "type": "destination"
                            },
                            "polylines": [],
                            "stopIds": [
                                "MTA_903130",
                                "MTA_400081",
                                "MTA_400083",
                                "MTA_400085",
                                "MTA_400086",
                                "MTA_400001",
                                "MTA_400002",
                                "MTA_400003",
                                "MTA_404120",
                                "MTA_404936",
                                "MTA_400007",
                                "MTA_403782",
                                "MTA_403316",
                                "MTA_405380",
                                "MTA_400352",
                                "MTA_400354",
                                "MTA_403456",
                                "MTA_403457",
                                "MTA_400561",
                                "MTA_400358",
                                "MTA_400360",
                                "MTA_400361",
                                "MTA_400362",
                                "MTA_400363",
                                "MTA_400364",
                                "MTA_405490",
                                "MTA_404994",
                                "MTA_400028",
                                "MTA_400029",
                                "MTA_400030",
                                "MTA_400031",
                                "MTA_400032",
                                "MTA_405567",
                                "MTA_400034",
                                "MTA_400036",
                                "MTA_400037",
                                "MTA_400038",
                                "MTA_400039",
                                "MTA_404191",
                                "MTA_400041",
                                "MTA_400042",
                                "MTA_400043",
                                "MTA_400044",
                                "MTA_400045",
                                "MTA_400046",
                                "MTA_400047",
                                "MTA_400048",
                                "MTA_404103",
                                "MTA_400050",
                                "MTA_400051",
                                "MTA_403980",
                                "MTA_400053",
                                "MTA_400054",
                                "MTA_400055",
                                "MTA_400056",
                                "MTA_400057",
                                "MTA_403711",
                                "MTA_400059",
                                "MTA_400060",
                                "MTA_400061",
                                "MTA_400062",
                                "MTA_404335",
                                "MTA_400065",
                                "MTA_400066",
                                "MTA_400067",
                                "MTA_803003"
                            ],
                            "subGroups": []
                        }
                    ],
        */
        Schema::create('mta_bus_route_stop_groups', function (Blueprint $table) {
            $table->id();
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
        Schema::dropIfExists('mta_bus_route_stop_groups');
    }
};
