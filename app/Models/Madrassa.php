<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Madrassa extends Model
{
    protected $fillable = [
        'name',
        'location_id'
    ];
}
