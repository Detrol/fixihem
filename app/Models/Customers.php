<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    use HasFactory;

    protected $fillable = ['first_name', 'last_name', 'personal_number', 'email', 'phone', 'door_code', 'billing_method', 'order_id', 'address', 'postal_code', 'city'];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'customer_id');
    }
}
