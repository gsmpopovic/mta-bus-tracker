<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MtaBusStop extends Model
{
    use HasFactory;

    public $fillable = [
        "code", // "100304",
        "direction", // "W",
        "id", // "MTA_100304",
        "lat", // 40.857836,
        "locationType", // 0,
        "lon", // -73.844305,
        "name", // "PELHAM PKWY/EASTCHESTER RD",
        "wheelchairBoarding", // "UNKNOWN"
    ];
}
