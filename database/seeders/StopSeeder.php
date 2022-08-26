<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Mta\BusTime\BusTime;
use App\Models\Stop;
use App\Models\Line;

class StopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lines = Line::all();
        
        $stops = BusTime::getUniqueStops();

        foreach($stops as $stop_point_ref => $stop){


            // throws an error because filter implicitly casts the collection to an array

            // $line = $lines->filter(function($item, $stop) {
            //     $bool = $item->line_ref == $stop["LineRef"];
            //     return $bool;
            
            // })->first();

           // $line = Line::where("line_ref", "=", $stop["LineRef"])->first();

           $line = null; 

           foreach($lines as $item){

            if(BusTime::normalizeLineRef($item->line_ref) == BusTime::normalizeLineRef($stop["LineRef"])){
                $line = $item;
            }

           }

            if(!is_object($line)){

                dd($line, $stop);

            }
        
            Stop::create([

                "visit_number"=>$stop["VisitNumber"],
                "stop_point_name"=>$stop["StopPointName"],
                "stop_point_ref"=>$stop_point_ref,
                "monitoring_ref"=>$stop["MonitoringRef"],
                "operator_ref"=>$stop["OperatorRef"],
                "line_id"=>$line->id,
                "line_ref"=>$stop["LineRef"],

            ]);

        }

    }
}
