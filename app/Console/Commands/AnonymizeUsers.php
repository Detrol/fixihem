<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Customers;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AnonymizeUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:anonymize-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $bookings = Booking::whereNull('user_id')->whereIn('status', [5,7])->get();

        foreach ($bookings as $booking) {
            if (Carbon::parse($booking->date)->lte(Carbon::today()->subDays(90))) {
                $customer = Customers::where('order_id', $booking->order_id)->first();
                if ($customer) {
                    $customer->first_name = 'Anonymiserad';
                    $customer->last_name = 'Anonymiserad';
                    $customer->email = 'Anonymiserad';
                    $customer->phone = 'Anonymiserad';
                    $customer->personal_number = 'Anonymiserad';
                    $customer->address = 'Anonymiserad';
                    $customer->save();
                }
            }
        }
    }
}
