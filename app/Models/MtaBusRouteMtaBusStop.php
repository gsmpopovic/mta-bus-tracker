<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MtaBusRouteMtaBusStop extends Model
{
    use HasFactory;

    public $fillable = [
        "mta_bus_stop_id",
        "mta_bus_route_id"
    ];

}
