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
        'status',
        'to_location_times',
        'special',
        'special_text'
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

    public function getExpectedPriceAttribute()
    {
        $price = ($this->expected_time / 60) * 249;

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

        return max($price, 200);
    }

    public function getEndPriceRutAttribute()
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
            if ($service->service && $service->service_options) {
                if ($service->has_own_materials) {
                    continue;
                }

                // Om $service->service_options redan är en array behöver vi inte dekoda den med json_decode
                $selectedOptions = json_decode($service->service_options, true);

                if ($selectedOptions && is_array($selectedOptions)) {
                    foreach ($selectedOptions as $selectedOption) {
                        // Kontrollera om tjänstealternativet har not_rut satt till 1
                        if (isset($selectedOption['not_rut']) && $selectedOption['not_rut'] == 1) {
                            continue; // Hoppa över detta tjänstealternativ och fortsätt med nästa i loopen
                        }

                        $optionPrice = floatval($selectedOption['price']) * ($selectedOption['quantity'] ?? 1);  // Multiplicera priset med kvantiteten, med en standardkvantitet av 1 om ingen kvantitet angiven
                        $servicePrice += $optionPrice;
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

        return max($timePrice + $servicePrice, 200);
    }

    public function getEndPriceNonRutAttribute()
    {
        $toLocationCompensation = $this->to_location_price * $this->to_location_times;
        $toCustomerCompensation = ($this->to_customer_price < 100) ? 0 : $this->to_customer_price;

        $nonRutServicePrice = 0;

        foreach ($this->services as $service) {
            if ($service->has_own_materials) {
                continue;
            }
            if ($service->service && $service->service_options) {
                $selectedOptions = json_decode($service->service_options, true);

                if ($selectedOptions && is_array($selectedOptions)) {
                    foreach ($selectedOptions as $selectedOption) {
                        if (isset($selectedOption['not_rut']) && $selectedOption['not_rut'] == 1) {
                            // Multiplicera priset med kvantiteten. Använd 1 som standardkvantitet om den inte anges.
                            $nonRutServicePrice += floatval($selectedOption['price']) * ($selectedOption['quantity'] ?? 1);
                        }
                    }
                }
            }
        }

        return $toLocationCompensation + $toCustomerCompensation + $nonRutServicePrice;
    }

    public function getEndPriceAttribute()
    {
        $startTime = \Carbon\Carbon::parse($this->start_time);
        $endTime = \Carbon\Carbon::parse($this->end_time);

        $durationInMinutes = $endTime->diffInMinutes($startTime);

        return max(($durationInMinutes / 60) * 249, 200);
    }

}
