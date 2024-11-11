<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    /** @use HasFactory<\Database\Factories\StudentFactory> */
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'first_name',
        'last_name',
        'student_name',
        'student_photo',
        'location_id',        
        'madrassa_id'
    ];
}
