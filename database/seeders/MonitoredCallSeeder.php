<?php

namespace Database\Seeders;

use App\Models\Journey;
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
        $journeys = Journey::all();

        $monitored_calls = BusTime::getMonitoredCalls();

        foreach ($monitored_calls as $monitored_call) {

            try {
                $journey = null;

                foreach ($journeys as $item) {

                    if ($item->vehicle_ref == $monitored_call["vehicle_ref"]) {

                        $journey = $item;

                    }

                }

                $monitored_call["journey_id"] = $journey->id;

                MonitoredCall::create($monitored_call);

            } catch (\Exception$e) {

                echo "\n" . $e->getMessage() . "\n";
                echo "\n Monitored Call, vehicle ref: " . $monitored_call["vehicle_ref"] . "\n";

            }

        }

    }
}
