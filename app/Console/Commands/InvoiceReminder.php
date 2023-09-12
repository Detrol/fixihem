<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Customers;
use Carbon\Carbon;
use Illuminate\Console\Command;

class InvoiceReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:invoice-reminder';

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
        $get_orders = Booking::where('status', 4)->get();

        foreach ($get_orders as $order) {

            $customer_details = Customers::where('order_id', $order->order_id)->first();

            if (Carbon::parse($order->updated_at)->addDays(6)->format('Y/m/d') == Carbon::today()->format('Y/m/d')) {
                $info['sms'] = null;
                $info['sms'] .= "Hej ".$customer_details->first_name.",\n\n";
                $info['sms'] .= "Jag hoppas att allt är bra med dig! Jag skriver bara för att påminna dig om att det har gått 6 dagar sedan jag utförde arbete hos dig och markerade det som fakturerat.\n\n";

                $info['sms'] .= "Här är lite information om din bokning:\n";
                $info['sms'] .= "Boknings-ID: ".$order->order_id."\n";
                $info['sms'] .= "Adress: ".$customer_details->address."\n";
                $info['sms'] .= "Datum: ".Carbon::parse($order->date_time)->format('d/m-y H:i')."\n\n";

                $info['sms'] .= "Enligt betalningsvillkoren har du 7 dagar på dig att betala, så du har en dag kvar att genomföra betalningen. Om du redan har betalat kan du bortse från detta meddelande. 😊\n\n";

                $info['sms'] .= "Tack så mycket för din förståelse och samarbete!\n";
                $info['sms'] .= "Vänligen notera att detta är ett automatiskt meddelande.";

                $sms = array(
                    'from' => 'Fixihem',
                    'to' => check_number($customer_details->phone),
                    'message' => $info['sms'],
                );
                sendSMS($sms);
            }
        }
    }
}
