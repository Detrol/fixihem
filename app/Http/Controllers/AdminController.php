<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingService;
use App\Models\Category;
use App\Models\Customers;
use App\Models\DriveLocations;
use App\Models\ServiceOption;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Assuming you have an is_admin field in your users table
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'is_admin' => true])) {
            return redirect()->intended('/admin');
        }

        return redirect()->back()->with('error', 'Invalid credentials or you are not an admin.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/admin/login');
    }

    public function today()
    {
        $orders = Booking::whereDate('date_time', Carbon::today())
            ->where('status', 2)
            ->orderBy('date_time')
            ->get();


        $order_details = [];

        foreach ($orders as $order) {
            $customer_details = Customers::where('order_id', $order->order_id)->first();

            $order_details[$order->id]['order_id'] = $order->order_id;
            $order_details[$order->id]['name'] = $customer_details->first_name . ' ' . $customer_details->last_name;
            $order_details[$order->id]['address'] = $customer_details->address;
            $order_details[$order->id]['date'] = Carbon::parse($order->date)->format('d/m-y - H:i');
            $order_details[$order->id]['price'] = $order->expected_price;
            $order_details[$order->id]['paid'] = $order_details[$order->id]['price'] * ((100 - 6 - 12.22) / 100);
            $order_details[$order->id]['distance_price'] = $order->to_customer_price;
            $order_details[$order->id]['travel_time'] = CarbonInterval::minutes($customer_details->travel_time)->cascade();
            $order_details[$order->id]['expected_time'] = gmdate("H:i:s", $order->expected_time * 60);
            $order_details[$order->id]['worked_time'] = Carbon::parse($order->start_time)->diffAsCarbonInterval($order->end_time)->format('%H:%I:%S');
            $order_details[$order->id]['start'] = $order->start_time;
            $order_details[$order->id]['stop'] = $order->end_time;
            $order_details[$order->id]['minutesWorked'] = Carbon::parse($order->start_time)->diffInMinutes($order->end_time);
            $order_details[$order->id]['hourlyRate'] = ($order_details[$order->id]['minutesWorked'] > 0) ? ($order->end_price_rut * 60) / $order_details[$order->id]['minutesWorked'] : 0;
            $order_details[$order->id]['hourlyRateNet'] = $order_details[$order->id]['hourlyRate'] * 0.8;  // Antar att netto är 80% av bruttot.
        }

        // Lägg till ytterligare bearbetning om du behöver det här

        return view('admin.today', compact('order_details'));
    }


    public function reserved()
    {
        return view('admin.reserved');
    }

    public function bookings(Request $request)
    {
        $bookings = Booking::with(['services', 'services.service.service_options'])->where('status', $request->status)->orderBy('date_time')->get();
        $title = null;

        $order_details = [];
        foreach ($bookings as $order) {
            $customer_details = Customers::where('order_id', $order->order_id)->first();

            $order_details[$order->id]['order_id'] = $order->order_id;
            $order_details[$order->id]['address'] = $customer_details->address;
            $order_details[$order->id]['name'] = $customer_details->first_name . ' ' . $customer_details->last_name;
            $order_details[$order->id]['date'] = Carbon::parse($order->date_time)->format('d/m-y - H:i');
            $order_details[$order->id]['price'] = $order->expected_price;
            $order_details[$order->id]['paid'] = $order_details[$order->id]['price'] * ((100 - 6 - 12.22) / 100);
            $order_details[$order->id]['distance_price'] = $order->to_customer_price;
            $order_details[$order->id]['travel_time'] = CarbonInterval::minutes($order->to_customer_time)->cascade();
            $order_details[$order->id]['expected_time'] = gmdate("H:i:s", $order->expected_time * 60);
            $order_details[$order->id]['worked_time'] = Carbon::parse($order->start_time)->diffAsCarbonInterval($order->end_time)->format('%H:%I:%S');
            $order_details[$order->id]['start'] = $order->start_time;
            $order_details[$order->id]['stop'] = $order->end_time;
            $order_details[$order->id]['status'] = $order->status;
            $order_details[$order->id]['minutesWorked'] = Carbon::parse($order->start_time)->diffInMinutes($order->end_time);
            $order_details[$order->id]['hourlyRate'] = ($order_details[$order->id]['minutesWorked'] > 0) ? ($order->end_price_rut * 60) / $order_details[$order->id]['minutesWorked'] : 0;
            $order_details[$order->id]['hourlyRateNet'] = $order_details[$order->id]['hourlyRate'] * 0.8;  // Antar att netto är 80% av bruttot.
            $order_details[$order->id]['spontaneous'] = $order->spontaneous;
            $order_details[$order->id]['customer_price'] = $order->end_price_rut + $order->end_price_non_rut;
            /*$order_details[$order->id]['customer_price'] = $order->customer_price + $order->to_customer_price;
            if ($order->to_location_distance !== null) {
                $order_details[$order->id]['customer_price'] += $order->to_location_price * $order->to_location_times;
            }*/
            $order_details[$order->id]['net_earnings'] = $order->net_earnings;
        }

        $earning = 0;
        $earning_before = 0;
        foreach ($bookings as $booking) {
            if ($booking->customer_type === 1) {
                $earning += $booking->expected_price * ((100 - 6 - 12.22) / 100);
            } else {
                $earning += $booking->expected_price * ((100 - 6 - 42.89) / 100);
            }

            $earning_before += $booking->invoiced;
        }

        // Du kan också behöva ändra logiken för beräkning av 'earning' om det fortfarande behövs.

        switch ($request->status) {
            case 1:
                $title = 'Obekräftade';
                break;
            case 2:
                $title = 'Bekräftade';
                break;
            case 3:
                $title = 'Utförda';
                break;
            case 4:
                $title = 'Fakturerade';
                break;
            case 5:
                $title = 'Betalda';
                break;
            case 6:
                $title = 'Pausade';
                break;
        }

        return view('admin.bookings', compact('bookings', 'title', 'order_details', 'earning', 'earning_before'));
    }

    public function order_accept(Request $request)
    {
        $order = Booking::where('order_id', $request->order_id)->first();
        $order->status = 2;
        $order->save();

        $customer_details = Customers::where('order_id', $order->order_id)->first();

        $info['sms'] = null;
        $info['sms'] .= "Din bokning har blivit bekräftad! \n\n";
        $info['sms'] .= "Boknings-ID: " . $order->order_id . " \n";
        $info['sms'] .= "Adress: " . $customer_details->address . " \n";
        $info['sms'] .= "Datum: " . Carbon::parse($order->date_time)->format('d/m-y H:i') . " \n\n";
        $info['sms'] .= "Du kommer att faktureras via Frilans Finans som du kan läsa mer om här: \n";
        $info['sms'] .= "https://frilansfinans.se/fakturamottagare";

        $sms = array(
            'from' => 'Fixihem',   /* Can be up to 11 alphanumeric characters */
            'to' => check_number($customer_details->phone),  /* The mobile number you want to send to */
            'message' => $info['sms'],
        );
        echo sendSMS($sms) . "\n";

        return redirect()->back();

    }

    public function order_completed(Request $request)
    {
        $order = Booking::where('order_id', $request->order_id)->first();
        $customer_details = Customers::where('order_id', $order->order_id)->first();

        $info['sms'] = null;
        $info['sms'] .= "Arbete utfört \n\n";
        $info['sms'] .= "Boknings-ID: " . $request->order_id . "\n";
        $info['sms'] .= "Adress: " . $customer_details->address . " \n";
        $info['sms'] .= "Datum: " . Carbon::parse($order->date_time)->format('d/m-y H:i') . " \n";
        $info['sms'] .= "Pris: " . number_format($order->end_price_rut + $order->end_price_non_rut) . " kr \n\n";

        $info['sms'] .= "Arbetet på din adress är nu utfört. Hoppas att du är nöjd med resultatet. \n\n";

        $info['sms'] .= "Faktura kommer inom 48 timmar(helgfri vardag) skickas via " . $customer_details->billing_method . ". Hanteras av frilans finans. Får du den ej inom tiden så hör av dig snarast! \n";
        $info['sms'] .= "Vid eventuell reseersättning eller tillägg för släp så får du en separat faktura för den.";

        $totalPrice = $order->end_price_rut;
        $price_excl = $totalPrice / 1.25;
        $order->invoiced = $price_excl * 2;
        $order->customer_price = $order->end_price_rut;
        $order->net_earnings = $order->end_price_rut * 0.8;

        $order->status = 3;
        $order->save();

        $sms = array(
            'from' => 'Fixihem',   /* Can be up to 11 alphanumeric characters */
            'to' => check_number($customer_details->phone),  /* The mobile number you want to send to */
            'message' => $info['sms'],
        );
        sendSMS($sms);

        return redirect()->back();

    }

    public function order_abort(Request $request)
    {
        $order = Booking::where('order_id', $request->order_id)->first();
        $customer_details = Customers::where('order_id', $order->order_id)->first();

        $info['sms'] = null;
        $info['sms'] .= "Boknings-ID: " . $order->order_id . " \n";
        $info['sms'] .= "Adress: " . $customer_details->address . " \n";
        $info['sms'] .= "Datum: " . Carbon::parse($order->date_time)->format('d/m-y H:i') . " \n\n";
        $info['sms'] .= "Din bokning har blivit makulerad, ingen vidare åtgärd krävs.";

        $order->status = 7;
        $order->save();

        $order->services()->delete();
        $customer_details->delete();
        $order->delete();

        $sms = array(
            'from' => 'Fixihem',   /* Can be up to 11 alphanumeric characters */
            'to' => check_number($customer_details->phone),  /* The mobile number you want to send to */
            'message' => $info['sms'],
        );
        echo sendSMS($sms) . "\n";

        return redirect()->back();

    }

    public function order_start_time(Request $request)
    {
        $order = Booking::where('order_id', $request->order_id)->first();
        $order->start_time = Carbon::now();
        $order->timestamps = false;
        $order->save();

        return redirect()->back();
    }

    public function order_stop_time(Request $request)
    {
        $order = Booking::where('order_id', $request->order_id)->first();
        $order->end_time = Carbon::now();

        $order->timestamps = false;
        $order->save();

        return redirect()->back();
    }


    public function order_details(Request $request)
    {
        $order = Booking::with(['services', 'services.service'])->where('order_id', $request->order_id)->first();
        $customer_details = Customers::where('order_id', $order->order_id)->first();

        // Modifiera tjänster med ytterligare data
        foreach ($order->services as $service) {
            $service->service_options_array = json_decode($service->service_options, true);
        }

        return view('admin.ajax.order_details', compact('order', 'customer_details'));
    }

    public function generateInvoiceText($orderId)
    {
        $order = Booking::with(['services', 'services.service', 'services.service.service_options'])
            ->where('order_id', $orderId)
            ->first();

        $services = $order->services; // All services
        $nonRutOptions = [];   // Non-RUT service options

        foreach ($order->services as $service) {
            $serviceOptionsArray = json_decode($service->service_options, true);
            if (is_array($serviceOptionsArray)) {
                foreach ($serviceOptionsArray as $option) {
                    if (isset($option['not_rut']) && $option['not_rut'] && !$service->has_own_materials) {
                        $nonRutOptions[] = $option;
                    }
                }
            }
        }

        if ($order->to_customer_price < 100) {
            $order->to_customer_price = 0;
        }

        $travelToCustomer = [
            'distance' => $order->to_customer_distance,
            'time' => $order->to_customer_time,
            'price' => $order->to_customer_price
        ];

        $travelToLocation = null;
        if ($order->services->contains(function ($service) {
            return $service->service->type == 'drive';
        })) {
            $travelToLocation = [
                'distance' => $order->to_location_distance,
                'time' => $order->to_location_time,
                'single_trip_price' => $order->to_location_price,
                'trips_count' => $order->to_location_times ?? 1,
                'total_price' => $order->to_location_price * ($order->to_location_times ?? 1)
            ];
        }

        // Calculate total price based on end_price
        $totalServices = $order->end_price; // This is the total for services based on time

        // Calculate non-RUT options and travel costs
        $totalNonRutAndTravel = $travelToCustomer['price'];
        if ($travelToLocation) {
            $totalNonRutAndTravel += $travelToLocation['total_price'];
        }
        foreach ($nonRutOptions as $option) {
            $optionQuantity = $option['quantity'] ?? 1;
            $totalNonRutAndTravel += $option['price'] * $optionQuantity;
        }

        $startTime = Carbon::createFromFormat('H:i:s', $order->start_time);
        $endTime = Carbon::createFromFormat('H:i:s', $order->end_time);
        $totalDuration = $endTime->diff($startTime);
        $totalTime = $totalDuration->format('%H timmar %I minuter');

        $totalPriceServices = $totalServices;
        $price_excl_services = $totalPriceServices / 1.25;
        $invoicedServices = $price_excl_services * 2;

        $price_excl_nonRutAndTravel = $totalNonRutAndTravel / 1.25;
        $invoicedNonRutAndTravel = $price_excl_nonRutAndTravel;

        return view('admin.ajax.invoice_text', compact('order', 'travelToCustomer', 'travelToLocation', 'totalServices', 'totalTime', 'services', 'nonRutOptions', 'totalNonRutAndTravel', 'invoicedServices', 'invoicedNonRutAndTravel'));
    }

    public function order_edit(Request $request)
    {
        $order = Booking::with(['services', 'services.service', 'services.service.service_options'])
            ->where('order_id', $request->order_id)
            ->firstOrFail();

        $services = $order->services;

        foreach ($services as $service) {
            $service->selected_option_ids = collect(json_decode($service->service_options, true))->pluck('id')->toArray();
        }

        $categories = Category::with('services', 'services.service_options')->where('active', 1)->get();

        $driveLocations = DriveLocations::all();

        return view('admin.order_edit', compact('order', 'categories', 'driveLocations'));
    }

    public function order_edit_submit(Request $request) {
        $order = Booking::where('order_id', $request->order_id)->first();

        $dateInput = $request->input('date');
        $timeInput = $request->input('time');

        // 1. Update the basic order details
        if ($timeInput !== null) {
            $combinedDateTime = Carbon::createFromFormat('Y-m-d H:i', "{$dateInput} {$timeInput}");
            $order->date_time = $combinedDateTime->toDateTimeString();
        }

        $selectedDriveLocationId = $request->input('drive_location_id');
        if ($selectedDriveLocationId) {
            $selectedDriveLocation = DriveLocations::find($selectedDriveLocationId);

            $origin = $selectedDriveLocation->address . ', ' . $selectedDriveLocation->city . ', ' . $selectedDriveLocation->postal_code . ', Sverige';
            $destination = $order->customer->address . ', ' . $order->customer->city . ', ' . $order->customer->postal_code . ', Sverige';

            $response = Http::get('https://maps.googleapis.com/maps/api/directions/json', [
                'origin' => $origin,
                'destination' => $destination,
                'mode' => 'car',
                'language' => 'sv-SE',
                'key' => 'AIzaSyAz9ZZf4KGBMl-zdh4MBJri6k-3E6VPCgY'
            ]);

            $distanceInMeters = $response->json()['routes'][0]['legs'][0]['distance']['value'];
            $durationInSeconds = $response->json()['routes'][0]['legs'][0]['duration']['value'];

            $order->to_location_distance = $distanceInMeters / 1000;
            $order->to_location_time = $durationInSeconds / 60;
            $order->to_location_price = $order->to_location_distance * 10;

            $order->save();
        }

        $order->comment = $request->input('comment');
        $order->email_reminder = $request->has('email_reminders');
        $order->sms_reminder = $request->has('sms_reminders');
        $order->addition_kr = $request->input('addition_kr');
        $order->addition_percent = $request->input('addition_percent');
        $order->addition_comment = $request->input('addition_comment');
        $order->deduction_kr = $request->input('deduction_kr');
        $order->deduction_percent = $request->input('deduction_percent');
        $order->deduction_comment = $request->input('deduction_comment');
        $order->to_location_times = $request->input('to_location_times');
        $order->save();

        // 2. Update the services for this order
        foreach ($request->input('service', []) as $serviceId => $serviceData) {
            $service = $order->services()->where('id', $serviceId)->first();

            if ($service) {
                // Uppdatera kvantitet och kommentarer
                if (isset($serviceData['quantity'])) {
                    $service->quantity = $serviceData['quantity'];
                } else {
                    $service->quantity = 1; // eller vilket standardvärde du vill sätta
                }

                $service->comments = $serviceData['comments'] ?? null;

                $currentOptions = json_decode($service->service_options, true) ?? [];

                // Uppdatera valda alternativ för denna tjänst
                if (isset($serviceData['options'])) {
                    $selectedOptionIds = $serviceData['options'];
                    $selectedOptions = ServiceOption::whereIn('id', $selectedOptionIds)->get()->toArray();

                    // Lägg till de nya valda alternativen till nuvarande alternativ
                    foreach ($selectedOptions as $selectedOption) {
                        $currentOptions[$selectedOption['id']] = $selectedOption;
                    }
                } else {
                    $selectedOptionIds = [];
                }

                // Rensa de borttagna alternativen från nuvarande alternativ
                foreach ($currentOptions as $id => $currentOption) {
                    if (!in_array($id, $selectedOptionIds)) {
                        unset($currentOptions[$id]);
                    }
                }

                $service->service_options = json_encode(array_values($currentOptions));
                $service->save();

                // Ytterligare logik om kunden har egna material
                if ($service->service->customer_materials === 'yes') {
                    $service->has_own_materials = isset($serviceData['has_own_materials']) && $serviceData['has_own_materials'] == 1;
                    $service->save();
                }
            }
        }

        // 3. Handle newly added services from the "Add services" section
        if ($request->has('add_services')) {
            $quantitiesInput = $request->input('service_quantity', []);
            $optionsInput = $request->input('add_options', []);
            $optionsQuantityInput = $request->input('add_option_quantity', []); // För att få kvantitet av valda alternativ
            $ownMaterialsInput = $request->input('has_own_materials', []);

            foreach ($request->input('add_services', []) as $serviceId) {
                $newService = new BookingService();
                $newService->service_id = $serviceId;
                $newService->booking_id = $order->id;

                if (isset($quantitiesInput[$serviceId])) {
                    $newService->quantity = $quantitiesInput[$serviceId];
                }

                // Kontrollera och spara de valda undertjänsterna
                if (isset($optionsInput) && is_array($optionsInput)) {
                    $selectedServiceOptions = ServiceOption::whereIn('id', $optionsInput)->get()->toArray();
                    $filteredOptions = [];

                    foreach ($selectedServiceOptions as $option) {
                        if ($option['has_quantity'] && isset($optionsQuantityInput[$option['id']])) {
                            $option['quantity'] = $optionsQuantityInput[$option['id']];
                        }
                        $filteredOptions[] = $option;
                    }

                    $newService->service_options = json_encode($filteredOptions);
                }

                if (isset($ownMaterialsInput[$serviceId]) && $ownMaterialsInput[$serviceId] == 1) {
                    $newService->has_own_materials = 1;
                } else {
                    $newService->has_own_materials = 0;
                }

                $newService->save();
            }
        }



        // 4. Handle removing services
        if($request->has('remove_services')) {
            foreach ($request->input('remove_services', []) as $serviceId) {
                $serviceToRemove = $order->services()->where('id', $serviceId)->first();
                if($serviceToRemove) {
                    $serviceToRemove->delete();
                }
            }
        }

        $totalEstimatedMinutes = 0;

        // Hämta alla tjänster kopplade till den aktuella bokningen
        $services = $order->services;

        foreach ($services as $service) {
            // Lägg till tjänstens uppskattade minuter multiplicerat med dess kvantitet
            $quantity = $service->quantity ?? 1;
            $totalEstimatedMinutes += $service->service->estimated_minutes * $quantity;

            // Om det finns undertjänster (options) för denna tjänst
            $serviceOptions = json_decode($service->service_options, true);
            if ($serviceOptions && is_array($serviceOptions)) {
                foreach ($serviceOptions as $option) {
                    // Lägg till undertjänstens uppskattade minuter multiplicerat med dess kvantitet
                    $totalEstimatedMinutes += $option['estimated_minutes'] * (isset($option['quantity']) ? $option['quantity'] : 1);
                }
            }
        }

        $order->expected_time = $totalEstimatedMinutes;
        $order->save();



        return redirect()->back()->with('success', 'Order updated successfully!');
    }



}
