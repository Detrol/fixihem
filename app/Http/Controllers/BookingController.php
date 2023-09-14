<?php

namespace App\Http\Controllers;

use App\Mail\Generic;
use App\Models\Booking;
use App\Models\BookingService;
use App\Models\Customers;
use App\Models\DriveLocations;
use App\Models\Service;
use App\Models\ServiceOption;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        // Bokningsdata direkt från requesten
        $bookingData = $request->only(['comment', 'email_reminder', 'sms_reminder']);

        // Resedata
        $travelData = [
            'to_customer_distance' => session('travel_distance'),
            'to_customer_time' => round(session('travel_duration')),
            'to_customer_price' => session('travel_distance') * 10,
            'to_location_distance' => session('location_distance'),
            'to_location_time' => round(session('location_duration')),
            'to_location_price' => session('location_distance') * 10,
        ];

        // Datum och tid
        $dateTime = $request->input('date') . ' ' . $request->input('time');

        // Prisinformation
        $price = session('price');
        $totalPrice = $price + session('travel_price') + session('location_price');

        // Uppdatera bokningsdata
        $bookingData = array_merge($bookingData, [
            'date_time' => $dateTime,
            'status' => 1,
            'expected_time' => session('expected_time'),
            'price' => $price,
            'total_price' => $totalPrice,
            'to_location_times' => session('travels', 1),
        ], $travelData);

        /*$extraTimeForDrive = 60;

        foreach (session('servicesData') as $service) {
            if ($service['drive_location'] === 'recycling') {
                $bookingData['expected_time'] += $extraTimeForDrive;
                break;
            }
        }*/

        try {
            DB::transaction(function () use ($bookingData, $request) {
                $uniqueOrderId = Str::lower(Str::random(random_int(6, 8)));

                $booking = Booking::create(array_merge($bookingData, ['order_id' => $uniqueOrderId]));

                $addressData = [
                    'address' => session('address'),
                    'postal_code' => session('postal_code'),
                    'city' => session('city')
                ];

                $customerData = array_merge($request->only(['first_name', 'last_name', 'personal_number', 'email', 'phone', 'door_code', 'billing_method']), ['order_id' => $booking->order_id], $addressData);

                $customer = Customers::create($customerData);

                $booking->update(['customer_id' => $customer->id]);

                $bookingServices = [];
                $servicesData = session('servicesData');
                $serviceQuantity = session('service_quantity');
                $comments = session('comments');
                $hasMaterial = session('has_material', []);

                foreach ($servicesData as $serviceId => $service) {
                    $bookingServices[] = [
                        'booking_id' => $booking->id,
                        'service_id' => $serviceId,
                        'service_options' => isset($service['options']) ? json_encode($service['options']) : null,
                        'quantity' => $serviceQuantity[$serviceId] ?? 1,
                        'has_own_materials' => (is_array($hasMaterial) && in_array($serviceId, $hasMaterial)) ? 1 : 0,
                        'comments' => $comments[$serviceId] ?? null,
                    ];
                }

                // Bulk insert
                BookingService::insert($bookingServices);
            });

            $info['sms'] = null;
            $info['sms'] .= "Din order har nu mottagits, du kommer få en bekräftelse så fort den har setts över.";

            $sms = array(
                'from' => 'Fixihem',   /* Can be up to 11 alphanumeric characters */
                'to' => check_number(request()->phone),  /* The mobile number you want to send to */
                'message' => $info['sms'],
            );
            echo sendSMS($sms) . "\n";

            $body = 'En bokning har kommit in.';

            $mailData = [
                'subject' => 'Ny bokning',
                'message' => $body,
            ];

            try {
                Mail::to('info@fixihem.se')->send(new Generic($mailData));
            } catch (Exception $e) {
                \Log::error("Kunde inte skicka mail: " . $e->getMessage());
            }

            session()->forget(['services', 'options', 'service_quantity', 'has_material', 'option_quantity', 'toralPrice', 'price', 'expected_time', 'serviceData', 'comments', 'travels']);

            return redirect()->route('home')->with('status', 'Din bokning har skapats! Du kommer få ett bekräftelse sms när den setts över.');
        } catch (\Exception $e) {
            // Lägg till lämplig felhantering här
            \Log::error("Något gick fel när du försökte boka: " . $e->getMessage());
            return redirect()->back()->with('error', 'Något gick fel när du försökte boka.');
        }
    }

    private function computeBookingData()
    {
        $import_services = session('services');
        $import_options = session('options');
        $import_service_quantity = session('service_quantity');
        $import_has_material = session('has_material');

        $servicesData = [];
        $serviceOptions = collect();

        $totalPrice = 0;
        $totalDuration = 0;  // Initialisera totalDuration till 0

        if ($import_options) {
            foreach ($import_options as $option) {
                $serviceOptions->push(ServiceOption::where('id', $option)->first());
            }
        }

        list($rutPrice, $rotPrice, $nonRotRutPrice) = $this->computeServicePrices($import_services, $import_service_quantity, $import_options, $import_has_material);

        // Först loopar vi för att kalkylera totalDuration
        foreach ($import_services as $service) {
            $get_service = Service::where('id', $service)->first();
            $quantity = $import_service_quantity[$service] ?? 1;
            $totalDuration += $get_service->duration * $quantity;
        }

        // Sedan loopar vi igen för att processa varje enskild tjänst
        foreach ($import_services as $service) {
            $get_service = Service::where('id', $service)->first();
            $quantity = $import_service_quantity[$service] ?? 1;

            $servicePrice = round($get_service->price * $quantity);
            $materialPrice = in_array($service, $import_has_material ?? [], true) ? 0 : round($get_service->material_price * $quantity);

            $totalPrice += $servicePrice + $materialPrice;

            foreach ($serviceOptions->where('service_id', $service) as $option) {
                $optionQuantity = session('option_quantity')[$option->id] ?? 1;
                $option->quantity = $optionQuantity;

                if (isset($option->estimated_minutes)) {
                    $totalDuration += $option->estimated_minutes * $optionQuantity;
                }

                if (isset($option->price) && !in_array($service, $import_has_material ?? [], true)) {
                    $totalPrice += round($option->price * $optionQuantity);
                }
            }

            $servicesData[$service] = [
                'name' => $get_service->name,
                'price' => round($servicePrice / $quantity),
                'material_price' => round($get_service->material_price),
                'customer_materials' => $get_service->customer_materials,
                'duration' => $get_service->duration,
                'quantity' => $quantity,
                'options' => $serviceOptions->where('service_id', $service) ?? null,
                'has_material' => is_array($import_has_material) && in_array($service, $import_has_material) ?? false,
                'is_rut' => $get_service->is_rut,
                'is_rot' => $get_service->is_rot,
                'drive_location' => $get_service->drive_location,
            ];
        }

        $servicePriceWithoutTravel = $this->computeTotalServicePrice($rutPrice, $rotPrice, $nonRotRutPrice);

        // Nu räknar vi ut en del av totalPrice baserat på totalDuration
        $totalPrice += ($totalDuration / 60) * 249;

        if ($totalPrice < 200) {
            $totalPrice = 200;
        }

        session(['totalPrice' => $totalPrice]);
        session(['price' => $servicePriceWithoutTravel]);
        if(!session()->has('expected_time')) {
            session(['expected_time' => $totalDuration]);
        }
        session(['servicesData' => $servicesData]);

        $containsROT = $rotPrice > 0;
        $containsRUT = $rutPrice > 0;
        $containsNonRUT = $nonRotRutPrice > 0;
        $hasMixed = ($containsRUT + $containsNonRUT + $containsROT) > 1;

        return compact('servicesData', 'totalPrice', 'hasMixed');
    }


    private function computeServicePrices($services, $quantities, $options, $hasMaterials)
    {
        $rutPrice = 0;
        $rotPrice = 0;
        $nonRotRutPrice = 0;

        // Samla tjänstinformation...
        $serviceOptions = collect();
        if ($options) {
            foreach ($options as $option) {
                $serviceOptions->push(ServiceOption::where('id', $option)->first());
            }
        }

        foreach ($services as $service) {
            $get_service = Service::where('id', $service)->first();
            $quantity = $quantities[$service] ?? 1;

            $servicePrice = round($get_service->price * $quantity);
            $materialPrice = in_array($service, $hasMaterials ?? [], true) ? 0 : round($get_service->price * $quantity);
            $combinedServicePrice = round($servicePrice + $materialPrice);

            foreach ($serviceOptions->where('service_id', $service) as $option) {
                if (isset($option->price) && !in_array($service, $hasMaterials ?? [], true)) {
                    $optionQuantity = session('option_quantity')[$option->id] ?? 1;  // Hämta tjänstalternativets kvantitet

                    if ($get_service->type === 'quantity' || $get_service->type === 'drive') {
                        $combinedServicePrice += round($option->price * $quantity);
                    } else {
                        $combinedServicePrice += round($option->price * $optionQuantity);
                    }
                }
            }

            if ($get_service->is_rot) {
                $rotPrice += $combinedServicePrice;
            } elseif ($get_service->is_rut) {
                $rutPrice += $combinedServicePrice;
            } else {
                $nonRotRutPrice += $combinedServicePrice;
            }
        }

        return [$rutPrice, $rotPrice, $nonRotRutPrice];
    }

    private function computeTotalServicePrice($rut, $rot, $nonRotRut)
    {
        $total = $rut + $rot + $nonRotRut;
        if ($total < 200) {
            $total = 200;
        }
        return $total;
    }

    public function getDistanceFromOriginToCustomer(Request $request)
    {
        $address = $request->input('address');
        $city = $request->input('city');
        $postalCode = $request->input('postal_code');

        // Spara adressinformationen i sessionen
        session(['address' => $address]);
        session(['city' => $city]);
        session(['postal_code' => $postalCode]);

        $fullAddress = "${address}, ${city}, ${postalCode}, Sverige";
        $origin = 'Rådmansgatan 4, Karlstad, 65462, Sverige';

        $response = Http::get('https://maps.googleapis.com/maps/api/directions/json', [
            'origin' => $origin,
            'destination' => $fullAddress,
            'mode' => 'car',
            'language' => 'sv-SE',
            'key' => 'AIzaSyAz9ZZf4KGBMl-zdh4MBJri6k-3E6VPCgY'
        ]);

        $distanceInMeters = $response->json()['routes'][0]['legs'][0]['distance']['value'];
        $distanceInKilometers = $distanceInMeters / 1000;

        $durationInSeconds = $response->json()['routes'][0]['legs'][0]['duration']['value'];
        $durationInMinutes = $durationInSeconds / 60;

        // Räkna ut priset baserat på 10 kr/km
        $price = $distanceInKilometers * 10;

        // Spara distans, restid och pris i sessionen
        session(['travel_distance' => $distanceInKilometers]);
        session(['travel_duration' => $durationInMinutes]);
        session(['travel_price' => $price]);

        return response()->json(['distance' => $distanceInKilometers, 'duration' => $durationInMinutes, 'price' => $price]);
    }


    public function getNearestDriveLocation(Request $request)
    {
        $address = $request->input('address');
        $city = $request->input('city');
        $postalCode = $request->input('postal_code');
        $serviceType = $request->input('service_type');
        $fullAddress = "${address}, ${city}, ${postalCode}, Sverige";

        $driveLocations = DriveLocations::where('type', $serviceType)
            ->get();

        $nearestLocation = null;
        $shortestDistance = PHP_INT_MAX;
        $shortestDuration = PHP_INT_MAX;

        foreach ($driveLocations as $location) {
            $origin = $location->address . ', ' . $location->city . ', ' . $location->postal_code . ', Sverige';

            $response = Http::get('https://maps.googleapis.com/maps/api/directions/json', [
                'origin' => $origin,
                'destination' => $fullAddress,
                'mode' => 'car',
                'language' => 'sv-SE',
                'key' => 'AIzaSyAz9ZZf4KGBMl-zdh4MBJri6k-3E6VPCgY'
            ]);

            $distanceInMeters = $response->json()['routes'][0]['legs'][0]['distance']['value'];
            $durationInSeconds = $response->json()['routes'][0]['legs'][0]['duration']['value'];

            if ($distanceInMeters < $shortestDistance) {
                $shortestDistance = $distanceInMeters;
                $shortestDuration = $durationInSeconds;
                $nearestLocation = $location;
            }

            sleep(1); // Väntar i en sekund
        }

        $distanceInKilometers = $shortestDistance / 1000;
        $durationInMinutes = $shortestDuration / 60;
        $price = $distanceInKilometers * 10;

        // Spara distans, restid och pris i sessionen med prefix "location_"
        session([
            'location_distance' => $distanceInKilometers,
            'location_duration' => $durationInMinutes,
            'location_price' => $price
        ]);

        return response()->json([
            'distance' => $distanceInKilometers,
            'duration' => $durationInMinutes,
            'price' => $price,
            'location_name' => $nearestLocation->name,
            'location_address' => $nearestLocation->address
        ]);
    }


    public function processStep1(Request $request)
    {
        // Hantera data från form
        $services = $request->input('services');
        $options = $request->input('options');
        $service_quantity = $request->input('service_quantity');
        $has_material = $request->input('has_material');
        $option_quantity = $request->input('option_quantity');

        //dd($options);

        // Här kan du göra vad du behöver med datan, t.ex. lagra den i en session.
        session(compact('services', 'options', 'service_quantity', 'has_material', 'option_quantity'));

        //dd($request->all());

        // Skicka användaren till steg 2
        return redirect()->route('booking.step2');
    }

    public function showStep2()
    {
        $data = $this->computeBookingData();
        return view('booking.step2', $data);
    }

    public function processStep2(Request $request)
    {
        // Hantera data från steg 2, t.ex. kommentarer.
        $comments = $request->input('comment');
        $travels = $request->input('to_location_times');

        // Kolla om antalet turer är större än 1. Om det är det, lägg till 15 minuter för varje extra tur.
        $extraTime = ($travels > 1) ? ($travels - 1) * 30 : 0;

        // Hämta det nuvarande värdet av 'expected_time' från sessionen
        $expectedTime = session('expected_time', 0); // om 'expected_time' inte finns, använd 0 som default
        $expectedTime += $extraTime;

        // Hämta den tidigare sparade datan från sessionen
        $servicesData = session('servicesData');

        // Spara all relevant data i sessionen
        session([
            'comments' => $comments,
            'travels' => $travels,
            'expected_time' => $expectedTime, // Spara den uppdaterade 'expected_time'
            'servicesData' => $servicesData
        ]);

        // Skicka användaren till steg 3
        return redirect()->route('booking.step3');
    }


    public function showStep3()
    {
        $data = $this->computeBookingData();

        $now = \Carbon\Carbon::now();

        if ($now->month > 6 && $now->month <= 12) {
            // Om det nu är efter juni men före december, sätt till mars nästa år.
            $endDate = $now->copy()->addYear()->startOfMonth(3)->format('Y-m-d');
        } elseif ($now->month > 12) {
            // Om det är efter december, sätt till mars i år.
            $endDate = $now->copy()->startOfMonth(3)->format('Y-m-d');
        } else {
            // Annars, behåll din nuvarande logik.
            $endDate = $now->endOfYear()->format('Y-m-d');
        }

        return view('booking.step3', $data, compact('endDate'));
    }

    public function saveAddressToSession(Request $request)
    {
        $request->session()->put('address', $request->input('address'));
        $request->session()->put('city', $request->input('city'));
        $request->session()->put('postal_code', $request->input('postal_code'));
        return response()->json(['status' => 'success']);
    }

    public function getAddressFromSession(Request $request)
    {
        $address = $request->session()->get('address');
        $city = $request->session()->get('city');
        $postal_code = $request->session()->get('postal_code');
        return response()->json(['address' => $address, 'city' => $city, 'postal_code' => $postal_code]);
    }

    public function saveRecyclingInfo(Request $request)
    {
        // Validera inkommande data (enligt dina behov)
        $validatedData = $request->validate([
            'location_name' => 'required|string',
            'recycling_distance' => 'required|string',
            'recycling_price' => 'required|string',
        ]);

        // Spara i session
        session(['recyclingInfo' => $validatedData]);

        return response()->json(['status' => 'success']);
    }

    public function getRecyclingInfo()
    {
        $recyclingInfo = session('recyclingInfo', null);

        if (!$recyclingInfo) {
            return response()->json(['status' => 'not_found'], 404);
        }

        return response()->json($recyclingInfo);
    }

    /*public function checkDate(Request $request)
    {
        $missionsToday = $this->getMissionsForDate($request->date, $request->order_id);
        $currentMissionDuration = $this->getCurrentMissionDuration($request->order_id);

        $startOfDay = Carbon::parse($request->date . ' ' . '09:00')->tz('Europe/Stockholm');
        $endOfDay = Carbon::parse($request->date . ' ' . '16:00')->tz('Europe/Stockholm')->subMinutes($currentMissionDuration);

        $busyTimes = $this->getBusyTimes($missionsToday, $startOfDay, $endOfDay, $currentMissionDuration);
        $freeTimes = $this->getFreeTimes($startOfDay, $endOfDay, $busyTimes);

        // Omvandla Carbon objekt till strängar i H:i format
        $formattedFreeTimes = [];
        foreach ($freeTimes as $time) {
            $formattedFreeTimes[] = Carbon::parse($time)->format('H:i');
        }

        return $formattedFreeTimes;
    }

    private function getMissionsForDate($date, $excludeOrderId)
    {
        $missions = Booking::whereIn('status', ['1', '2'])
            ->where('order_id', '!=', $excludeOrderId)
            ->get();

        return $missions->filter(function ($mission) use ($date) {
            return Carbon::parse($mission->date_time)->tz('Europe/Stockholm')->isSameDay($date);
        });
    }

    private function getCurrentMissionDuration($order_id)
    {
        if ($order_id){
            $mission = Booking::findOrFail($order_id);

            $travelTime = $mission->to_customer_time;
            $extraMinutes = ($travelTime * 2) + 15;
            $expectedEndTime = Carbon::now()->addMinutes(max($mission->expected_time, 60))->addMinutes($extraMinutes);
        } else {
            $travelTime = session('travel_duration');
            $extraMinutes = ($travelTime * 2) + 15;
            $expectedEndTime = Carbon::now()->addMinutes(max(session('expectedTime'), 60))->addMinutes($extraMinutes);
        }

        return $expectedEndTime->diffInMinutes(Carbon::now());
    }

    private function getBusyTimes($missionsToday, $startOfDay, $endOfDay, $currentMissionDuration)
    {
        $busyTimes = collect();

        foreach ($missionsToday as $mission) {
            $missionStart = $this->getMissionStart($mission, $startOfDay);

            // Denna period representerar de upptagna tiderna för detta uppdrag, baserat på dess varaktighet.
            $busyPeriod = new CarbonPeriod($missionStart, $missionStart->copy()->addMinutes($currentMissionDuration), CarbonInterval::minutes(15));

            foreach ($busyPeriod as $busyTime) {
                $busyTimes->push($busyTime);
            }
        }

        return $busyTimes->unique()->sort();
    }

    private function getFreeTimes($startOfDay, $endOfDay, $busyTimes)
    {
        $allTimes = collect(CarbonInterval::minutes('15')->toPeriod($startOfDay, $endOfDay));

        return $allTimes->diff($busyTimes)->map(function ($time) {
            return $time->format('H:i');
        })->all();
    }

    private function getMissionStart($mission, $startOfDay)
    {
        $travelTime = Booking::where('order_id', $mission->order_id)->first()->to_customer_time;
        $missionStart = Carbon::parse($mission->date_time)->subMinutes($travelTime + 15)->tz('Europe/Stockholm');

        return $missionStart->lt($startOfDay) ? $startOfDay : $missionStart;
    }

    private function getMissionEnd($mission, $currentMissionDuration)
    {
        $travelTime = Booking::where('order_id', $mission->order_id)->first()->to_customer_time;

        return Carbon::parse($mission->date_time)
            ->addSeconds(max($mission->expected_time, 3600))
            ->addMinutes($travelTime + 15 + $currentMissionDuration)
            ->tz('Europe/Stockholm');
    }*/

    function checkDate(Request $request)
    {
        $missions_today = collect();
        $busy_between = collect();
        $busy_times = collect();
        $times = array();

        $missions = Booking::whereIn('status', ['1', '2'])->where('id', '!=', $request->order_id)->get();

        $current_mission = Booking::where('id', $request->order_id)->first();
        if (!isset($current_mission)) {
            $current_travel_time = session('travel_duration');
        } else {
            $current_travel_time = $current_mission->to_customer_time;
        }

        foreach ($missions as $mission) {
            if (Carbon::parse($mission->date_time)->tz('Europe/Stockholm')->format('Y-m-d') == $request->date) {
                $missions_today->add($mission);
            }
        }

        try {
            // Hämta data från andra databasen
            $missionsFromSecondDb = DB::connection('second_db')->table('customer_bookings')->whereIn('status', ['1', '2'])->get();
            $visitsFromSecondDb = DB::connection('second_db')->table('customer_visits')->where('status', 1)->get();

            foreach ($missionsFromSecondDb as $mission) {
                if (Carbon::parse($mission->date)->tz('Europe/Stockholm')->format('Y-m-d') == $request->date) {
                    $missions_today->add($mission);
                }
            }

            foreach ($visitsFromSecondDb as $visit) {
                if (Carbon::parse($visit->date)->tz('Europe/Stockholm')->format('Y-m-d') == $request->date) {
                    $missions_today->add($visit);
                }
            }
        } catch (\Exception $e) {
            // Här hanteras eventuella fel som uppstår när du försöker ansluta till den andra databasen eller hämta data
            // Du kan logga felet eller göra något annat beroende på ditt behov
            \Log::error("Error connecting to second database: " . $e->getMessage());
        }

        $start = Carbon::parse($request->date . ' 09:00')->tz('Europe/Stockholm');
        $end = Carbon::parse($request->date . ' 16:00')->tz('Europe/Stockholm');

        $add_minutes = ($current_travel_time * 2) + 15;

        if (!isset($current_mission->expected_time)) {
            $current_mission_end_from_now = Carbon::now()->addMinutes(max(session('expected_time'), 60))->addMinutes($add_minutes)->tz('Europe/Stockholm');
        } else {
            $current_mission_end_from_now = Carbon::now()->addMinutes(max($current_mission->expected_time, 60))->addMinutes($add_minutes)->tz('Europe/Stockholm');
        }
        $current_mission_length = roundUpToAny($current_mission_end_from_now->diffInMinutes(Carbon::now()));

        // Vi vill att arbetet slutar innan dagen slutar
        $end = $end->subMinutes($current_mission_length);

        foreach ($missions_today as $mission) {
            $db = false;
            if (isset($mission->to_customer_time)) {
                $mission_travel_time = $mission->to_customer_time;
            } else {
                if ($mission->user_id) {
                    $mission_travel_time = DB::connection('second_db')->table('customer_details_users')->where('user_id', $mission->user_id)->first()->travel_time;
                } elseif ($mission->unique_id) {
                    $mission_travel_time = DB::connection('second_db')->table('customer_visits')->where('unique_id', $mission->unique_id)->first()->travel_time;
                } else {
                    $mission_travel_time = DB::connection('second_db')->table('customer_details')->where('order_id', $mission->order_id)->first()->travel_time;
                }

                $db = true;
            }

            $mission_travel_time = roundUpToAny($mission_travel_time);

            $sub_minutes = $mission_travel_time + 15;

            $date = $mission->date_time ?? $mission->date;

            $mission_start = Carbon::parse($date)->subMinutes($sub_minutes)->tz('Europe/Stockholm');
            if ($mission_start->lt($start)) {
                $mission_start = $start;
            }

            $add_minutes = $mission_travel_time + 15;

            if ($db) {
                $mission_end = Carbon::parse($date)->addSeconds(max($mission->expected_time, 3600))->addMinutes($add_minutes)->tz('Europe/Stockholm');
            } else {
                $mission_end = Carbon::parse($date)->addMinutes(max($mission->expected_time, 60))->addMinutes($add_minutes)->tz('Europe/Stockholm');
            }

            $busy_between->add(['start' => $mission_start, 'end' => $mission_end]);
        }

        // RESERVED TIMES
        /*$reservedTimes = ReservedTimes::where('date', Carbon::parse($request->date)->format('Y-m-d'))->first();
        if ($reservedTimes?->from && $reservedTimes?->to) {
            $busy_between->add(['start' => Carbon::parse($request->date . ' ' . $reservedTimes->from)->tz('Europe/Stockholm'), 'end' => Carbon::parse($request->date . ' ' . $reservedTimes->to)->tz('Europe/Stockholm')]);
        }

        // SPECIAL TIMES
        $specialTimes = SpecialTimes::where('date', Carbon::parse($request->date)->format('Y-m-d'))->first();
        if ($specialTimes?->from && $specialTimes?->to) {
            $busy_between->add(['start' => Carbon::parse($request->date . ' ' . $specialTimes->from)->tz('Europe/Stockholm'), 'end' => Carbon::parse($request->date . ' ' . $specialTimes->to)->tz('Europe/Stockholm')]);
        }*/

        if(request()->getHost() == 'localhost') {
            try {
                // Hämta data från andra databasen
                $reservedTimes = DB::connection('second_db')->table('reserved_times')->where('date', Carbon::parse($request->date)->format('Y-m-d'))->first();
                $specialTimes = DB::connection('second_db')->table('special_times')->where('date', Carbon::parse($request->date)->format('Y-m-d'))->first();

                // RESERVED TIMES
                if ($reservedTimes?->from && $reservedTimes?->to) {
                    $busy_between->add(['start' => Carbon::parse($request->date . ' ' . $reservedTimes->from)->tz('Europe/Stockholm'), 'end' => Carbon::parse($request->date . ' ' . $reservedTimes->to)->tz('Europe/Stockholm')]);
                }

                // SPECIAL TIMES
                if ($specialTimes?->from && $specialTimes?->to) {
                    $busy_between->add(['start' => Carbon::parse($request->date . ' ' . $specialTimes->from)->tz('Europe/Stockholm'), 'end' => Carbon::parse($request->date . ' ' . $specialTimes->to)->tz('Europe/Stockholm')]);
                }
            } catch (\Exception $e) {
                // Här hanteras eventuella fel som uppstår när du försöker ansluta till den andra databasen eller hämta data
                // Du kan logga felet eller göra något annat beroende på ditt behov
                \Log::error("Error connecting to second database: " . $e->getMessage());
            }
        }



        $busy_times = collect();

        $intervals = CarbonInterval::minutes('15')->toPeriod($start, $end);
        foreach ($intervals as $time) {
            $this_time = Carbon::parse($time)->tz('Europe/Stockholm');

            // 10 minutes extra for preparations
            $add_minutes = $current_mission_length + 10;

            $current_mission_end_from_time = Carbon::parse(roundToNearestMinuteInterval($this_time->addMinutes($add_minutes)));

            if ($busy_between->isNotEmpty()) {
                foreach ($busy_between as $busy) {
                    if ($time->between($busy['start'], $busy['end'])) {
                        $busy_times->add($time->format('H:i'));
                    }

                    $exitingAppointment = CarbonPeriod::create($busy['start'], $busy['end']);
                    $appointmentTentative = CarbonPeriod::create($time, $current_mission_end_from_time);

                    if ($exitingAppointment->overlaps($appointmentTentative)) {
                        $busy_times->add($time->format('H:i'));
                    }
                }
            }
        }

        $busy_times = $busy_times->unique()->sort();

        foreach ($intervals as $time) {
            if (!$busy_times->contains($time->format('H:i'))) {
                //$free_times->add($time->format('H:i'));
                $times[] = $time->format('H:i');
            }
        }

        echo json_encode(array_unique($times));
    }


    /*
     *
     * HANTERA BOKNINGAR
     *
     */


}
