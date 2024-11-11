<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'payment_date', 
        'student_id', 
        'amount_paid', 
        'receipt_number'
    ];
}
