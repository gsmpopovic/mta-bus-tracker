<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MtaBusRouteStopGroup extends Model
{
    use HasFactory;

    public $fillable = [
        "name",
        "type",
        "mta_bus_route_id",
        "line_ref",
        "stop_ids"
    ];

}
