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
        'price',
        'total_price',
        'customer_id',
        'comment',
        'expected_time',
        'to_customer_distance',
        'to_customer_time',
        'to_customer_price',
        'to_location_distance',
        'to_location_time',
        'to_location_price',
    ];

    public function getStatusNameAttribute() {
        $statuses = [
            1 => 'Obekräftad',
            2 => 'Bekräftad',
            3 => 'Utförd',
            4 => 'Fakturerad',
            5 => 'Avbokad',
            6 => 'Pausad'
        ];

        return $statuses[$this->status] ?? 'Unknown';
    }

    public function services()
    {
        return $this->hasMany(BookingService::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customers::class);
    }
}
