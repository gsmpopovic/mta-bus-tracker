<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoredCall extends Model
{
    use HasFactory;

    public $fillable = [
        "vehicle_ref",
        "stop_id",
        "journey_id",
        "aimed_arrival_time",
        "expected_arrival_time",
        "aimed_departure_time",
        "expected_departure_time",
        "presentable_distance",
        "distance_from_call",
        "stops_from_call",
        "call_distance_along_route",
        "stop_point_ref",
        "visit_number",
        "stop_point_name",
    ];

}
