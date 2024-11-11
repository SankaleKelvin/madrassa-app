<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    protected $fillable = [
        'billing_date', 
        'student_course_id', 
        'invoice_number', 
        'amount_charged'
    ];
}
