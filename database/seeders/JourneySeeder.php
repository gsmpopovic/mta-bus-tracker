<?php

namespace Database\Seeders;

use App\Models\Journey;
use App\Mta\BusTime\BusTime;
use Illuminate\Database\Seeder;

class JourneySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $journeys = BusTime::getJourneys();

        foreach ($journeys as $journey) {

            try {

                Journey::create($journey);

            } catch (\Exception$e) {

                echo "\n" . $e->getMessage() . "\n";
            
            }

        }

    }
}
