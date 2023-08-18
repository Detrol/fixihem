<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingService extends Model
{
    use HasFactory;

    protected $fillable = ['booking_id', 'service_id', 'service_option_id', 'quantity', 'has_own_materials', 'comments'];

}
