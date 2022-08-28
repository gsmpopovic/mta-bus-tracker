<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journey extends Model
{
    use HasFactory;

    public $fillable = [
        "line_ref",
        "direction_ref",
        "journey_pattern_ref",
        "published_line_name",
        "operator_ref",
        "destination_name",
        "origin_aimed_departure_time",
        "monitored",
        "vehicle_longitude",
        "vehicle_latitude",
        "bearing",
        "progress_rate",
        "progress_status",
        "block_ref",
        "vehicle_ref",
    ];

    public function monitored_calls(){
        return $this->hasOne(\App\Models\MonitoredCall::class, "journey_id", "id");
    }

}
