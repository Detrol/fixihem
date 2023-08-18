<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_time',
        'order_id',
        'total_price',
        'customer_id',
        'comment',
        'expected_time'
    ];
}
