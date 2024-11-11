<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentCourse extends Model
{
    protected $fillable = [
        'enrollment_date',
        'amount_charged',
        'student_id',
        'course_id'
    ];
}
