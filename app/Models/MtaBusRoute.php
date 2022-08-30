<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MtaBusRoute extends Model
{
    use HasFactory;

    public $fillable = [
        "line_ref",
        "short_name",
        "long_name",
        "description",
        "type",
        "color",
        "text_color",
        "agency_id",
    ];

}
