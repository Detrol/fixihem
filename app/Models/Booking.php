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
        'email_reminder',
        'sms_reminder',
        'status'
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
        return $this->belongsTo(Customers::class, 'customer_id');
    }

    public function getTotalPriceAttribute()
    {
        $price = ($this->expected_time / 60) * 299;

        foreach ($this->services as $service) {
            // Om kunden har sitt eget material, hoppa över denna tjänst i prisberäkningen
            if($service->has_own_materials) {
                continue;
            }

            // Annars, lägg till tjänstpriset (om det finns)
            if ($service->price) {
                $price += $service->price;
            }

            // Och lägg till priset av alla tjänstealternativ om de finns
            if($service->service && $service->service_options) {
                $selectedOptions = json_decode($service->service_options, true);

                if($selectedOptions && is_array($selectedOptions)) {
                    foreach ($selectedOptions as $selectedOption) {
                        if(isset($selectedOption['price'])) {
                            $price += floatval($selectedOption['price']);
                        }
                    }
                }
            }
        }

        // Lägg till additioner i kronor
        if ($this->addition_kr) {
            $price += $this->addition_kr;
        }

        // Lägg till additioner i procent
        if ($this->addition_percent) {
            $price += ($this->addition_percent / 100) * $price;
        }

        // Dra av avdrag i kronor
        if ($this->deduction_kr) {
            $price -= $this->deduction_kr;
        }

        // Dra av avdrag i procent
        if ($this->deduction_percent) {
            $price -= ($this->deduction_percent / 100) * $price;
        }

        return max($price, 300);
    }



    public function getEndPriceTotalAttribute()
    {
        $timePrice = $this->end_price;
        $servicePrice = 0;

        foreach ($this->services as $service) {
            // Om kunden har sitt eget material, hoppa över denna tjänst i prisberäkningen
            if($service->has_own_material) {
                continue;
            }

            // Lägg till tjänstpriset (om det finns)
            if ($service->price) {
                $servicePrice += $service->price;
            }

            // Och lägg till priset av alla tjänstealternativ om de finns
            if($service->service && $service->service_options) {
                $selectedOptionIds = json_decode($service->service_options, true);

                if($selectedOptionIds && is_array($selectedOptionIds)) {
                    foreach ($selectedOptionIds as $selectedOptionId) {
                        $option = $service->service->service_options->where('id', $selectedOptionId)->first();
                        if($option) {
                            $servicePrice += $option->price;
                        }
                    }
                }
            }
        }

        // Lägg till additioner i kronor
        if ($this->addition_kr) {
            $servicePrice += $this->addition_kr;
        }

        // Lägg till additioner i procent
        if ($this->addition_percent) {
            $servicePrice += ($this->addition_percent / 100) * $servicePrice;
        }

        // Dra av avdrag i kronor
        if ($this->deduction_kr) {
            $servicePrice -= $this->deduction_kr;
        }

        // Dra av avdrag i procent
        if ($this->deduction_percent) {
            $servicePrice -= ($this->deduction_percent / 100) * $servicePrice;
        }

        return max($timePrice + $servicePrice, 300);
    }



    public function getEndPriceAttribute()
    {
        $startTime = \Carbon\Carbon::parse($this->start_time);
        $endTime = \Carbon\Carbon::parse($this->end_time);

        $durationInMinutes = $endTime->diffInMinutes($startTime);

        return max(($durationInMinutes / 60) * 299, 300);
    }

}
