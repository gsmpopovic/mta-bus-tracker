<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stop extends Model
{
    use HasFactory;

    public $fillable = [
        "stop_point_ref",
        "stop_point_name",
        "monitoring_ref",
        "operator_ref",
        "visit_number",
        "line_id",
        "line_ref",
    ];
}
