<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MtaBusRouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $bustime = new \App\Mta\BusTime\BusTime();

        $bustime->seedObaMtaRoutes();
        
    }
}
