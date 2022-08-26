<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Line extends Model
{
    use HasFactory;

    public $fillable = [
        "line_ref",
        "published_line_name"
    ];

    public function buses(){
        return $this->hasMany("App\Models\Bus", "line_id");
    }

}
