<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;
use Database\Seeders\LineSeeder;
use Database\Seeders\StopSeeder;
use Database\Seeders\JourneySeeder;
use Database\Seeders\MonitoredSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $line_seeder = new LineSeeder;
        $line_seeder->run();

        $stop_seeder = new StopSeeder;
        $stop_seeder->run();

        $journey_seeder = new JourneySeeder;
        $journey_seeder->run();

        $monitored_call = new MonitoredCallSeeder;
        $monitored_call->run();

    }
}
