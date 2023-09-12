<?php

namespace App\Console\Commands;

use App\Mail\Generic;
use App\Models\Booking;
use App\Models\Customers;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class BookingReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:booking-reminder';

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
        $get_orders = Booking::where('status', 2)->get();

        foreach ($get_orders as $order) {
                $customer_details = Customers::where('order_id', $order->order_id)->first();

            // E-Post
            if ($order->email_reminder == 1) {
                if (Carbon::parse($order->date)->format('Y/m/d') == Carbon::today()->addDays(1)->format('Y/m/d')) {
                    $body = 'Hej '.$customer_details->first_name.'!<br />
                    Jag vill bara påminna dig om att du har bokat en tid hos mig som ska utföras imorgon..<br /><br />

                    <strong>Boknings-ID:</strong> '.$order->order_id.'<br />
                    <strong>Datum:</strong> '.Carbon::parse($order->date_time)->format('d/m-y H:i').'<br />
                    <strong>Adress:</strong> '.$customer_details->address.'<br /><br />

                    <small><i>Vänligen notera att detta mailet är automatiskt, och går inte att svara på.</i></small>';

                    $mailData = [
                        'subject' => 'Påminnelse',
                        'message' => $body,
                    ];

                    Mail::to($customer_details->email)->send(new Generic($mailData));
                }
            }

            // SMS
            if ($order->sms_reminder == '1') {
                if (Carbon::parse($order->date)->format('Y/m/d') == Carbon::today()->addDays(1)->format('Y/m/d')) {

                    $info['sms'] = null;
                    $info['sms'] .= "Hej ".$customer_details->first_name."! \n\n";
                    $info['sms'] .= "Jag vill bara påminna dig om att du har bokat en tid hos mig som ska utföras imorgon. \n\n";

                    $info['sms'] .= "Boknings-ID: ".$order->order_id." \n";
                    $info['sms'] .= "Adress: ".$customer_details->address." \n";
                    $info['sms'] .= "Datum: ".Carbon::parse($order->date_time)->format('d/m-y H:i')." \n\n";

                    $info['sms'] .= "Vänligen notera att detta meddelandet är automatiskt.";

                    $sms = array(
                        'from' => 'Fixihem',
                        'to' => check_number($customer_details->phone),
                        'message' => $info['sms'],
                    );
                    sendSMS ($sms);

                }

            }
        }
    }
}
