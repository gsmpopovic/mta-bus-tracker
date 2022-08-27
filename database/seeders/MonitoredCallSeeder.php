<?php

namespace Database\Seeders;

use App\Models\Journey;
use App\Models\Stop;
use App\Models\MonitoredCall;
use App\Mta\BusTime\BusTime;
use Illuminate\Database\Seeder;

class MonitoredCallSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //$journeys = Journey::all();

        $journeys = \App\Models\Journey::all()->pluck('id', 'vehicle_ref')->toArray();
        $stops = \App\Models\Stop::all()->pluck('id', 'stop_point_ref')->toArray();

        $monitored_calls = BusTime::getMonitoredCallsSeed();

        foreach ($monitored_calls as $monitored_call) {

            try {


                if(isset($journeys[$monitored_call["vehicle_ref"]]) && isset($stops[$monitored_call["stop_point_ref"]])){

                    $journey_id = $journeys[$monitored_call["vehicle_ref"]];

                    $stop_id = $stops[$monitored_call["stop_point_ref"]];
                
                    $monitored_call["journey_id"] = $journey_id;
                    $monitored_call["stop_id"] = $stop_id;

                    MonitoredCall::create($monitored_call);

                }

            } catch (\Exception$e) {

                echo "\n" . $e->getMessage() . "\n";
                echo "\n Monitored Call, vehicle ref: " . $monitored_call["vehicle_ref"] . "\n";

            }

        }

    }
}
