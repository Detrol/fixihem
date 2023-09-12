<?php

namespace App\Console\Commands;

use App\Mail\Generic;
use App\Models\Booking;
use App\Models\Customers;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendReview extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-review';

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
        //if (settings('order_review' == 1)) {
        $get_orders = Booking::whereIn('status', [4,5])->get();

        foreach ($get_orders as $order) {

            $customer_details = Customers::where('order_id', $order->order_id)->first();

            if ($customer_details === null) {
                // Handle the case where customer details are not found
                continue;
            }

            if (Carbon::parse($order->date)->isYesterday()) {
                $body = 'Hej '.$customer_details->first_name.'!<br />
                Igår hade jag nöjet att utföra arbete hos dig. Jag hoppas att du är nöjd med resultatet! Om så är fallet skulle jag vara väldigt tacksam om du kunde ta några minuter att lämna ett omdöme. <br />
                Ditt stöd hjälper mig enormt att bygga förtroende och utöka min kundkrets. Och tveka inte att rekommendera mig till dina vänner, kollegor eller familj!<br /><br />

                För att lämna ett omdöme på Facebook, klicka här:
                https://www.facebook.com/profile.php?id=61551417772490&sk=reviews

                För att lämna ett omdöme på Google, klicka här:
                https://g.page/r/CXOhT9nvX1qyEAI/review

                Tack på förhand, och jag hoppas att vi ses igen snart!<br /><br />

                <small><i>Vänligen notera att detta mail är automatiskt genererat.</i></small>';

                $mailData = [
                    'subject' => 'Hoppas du är nöjd med arbetet!',
                    'message' => $body,
                ];

                try {
                    Mail::to($customer_details->email)->send(new Generic($mailData));
                } catch (Exception $e) {
                    // Handle the case where the email could not be sent
                }
            }
        }
    }
}
