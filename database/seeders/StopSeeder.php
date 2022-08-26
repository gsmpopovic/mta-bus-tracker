<?php

namespace Database\Seeders;

use App\Models\Line;
use App\Models\Stop;
use App\Mta\BusTime\BusTime;
use Illuminate\Database\Seeder;

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

        foreach ($stops as $stop_point_ref => $stop) {

            try {
                // throws an error because filter implicitly casts the collection to an array

                // $line = $lines->filter(function($item, $stop) {
                //     $bool = $item->line_ref == $stop["LineRef"];
                //     return $bool;

                // })->first();

                //$line = Line::where("line_ref", "=", $stop["LineRef"])->first();

                $line = null;

                foreach ($lines as $item) {

                    if ($item->line_ref == $stop["LineRef"]) {
                        $line = $item;
                    }

                }

                Stop::create([

                    "visit_number" => $stop["VisitNumber"],
                    "stop_point_name" => $stop["StopPointName"],
                    "stop_point_ref" => $stop_point_ref,
                    "monitoring_ref" => $stop["MonitoringRef"],
                    "operator_ref" => $stop["OperatorRef"],
                    "line_id" => $line->id,
                    "line_ref" => $stop["LineRef"],

                ]);
            } catch (\Exception$e) {
                echo $e->getMessage();
            }

        }

    }
}
