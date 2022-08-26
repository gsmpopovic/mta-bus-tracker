<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Mta\BusTime\BusTime;
use App\Models\Line; 

class LineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $lines = BusTime::getUniqueLines();

        foreach($lines as $published_line_name => $line_ref){

            Line::create([
                "line_ref" => $line_ref,
                "published_line_name" => $published_line_name
            ]);
        }

    }
}
