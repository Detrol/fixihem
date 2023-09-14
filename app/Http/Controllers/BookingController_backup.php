<?php

namespace App\Http\Controllers;

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
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        // Bokningsdata direkt från requesten
        $bookingDataFromRequest = $request->only(['comment', 'email_reminder', 'sms_reminder']);

        // Hämta data från sessionen
        $sessionData = [
            'expected_time' => session('expected_time'),
        ];

        $travelData = [
            'to_customer_distance' => session('travel_distance'),
            'to_customer_time' => round(session('travel_duration')),
            'to_customer_price' => session('travel_distance') * 10,
            'to_location_distance' => session('location_distance'),
            'to_location_time' => round(session('location_duration')),
            'to_location_price' => session('location_distance') * 10,
        ];

        $date = $request->input('date');
        $time = $request->input('time');
        $date_time = $date . ' ' . $time;

        $finalBookingData = array_merge(
            $bookingDataFromRequest,
            ['date_time' => $date_time, 'status' => 1],
            $sessionData,
            $travelData
        );

        $total_price = session('price') + session('travel_price') + session('location_price');

        $finalBookingData['price'] = session('price');
        $finalBookingData['total_price'] = $total_price;

        DB::transaction(function () use ($finalBookingData, $request) {
            // 1. Generera en unik order-id
            $uniqueOrderId = Str::lower(Str::random(random_int(6, 8)));

            // 2. Skapa bokning med den unika order-id:n, men utan customer_id
            $bookingData = array_merge($finalBookingData, ['order_id' => $uniqueOrderId]);
            $booking = Booking::create($bookingData);

            // Skapa kunden med den unika order-id:n
            $addressData = [
                'address' => session('address'),
                'postal_code' => session('postal_code'),
                'city' => session('city'),
            ];

            $customerData = array_merge(
                $request->only(['first_name', 'last_name', 'personal_number', 'email', 'phone', 'door_code', 'billing_method']),
                ['order_id' => $booking->order_id], $addressData
            );
            $customer = Customers::create($customerData);

            // 3. Uppdatera bokningen med customer_id
            $booking->update(['customer_id' => $customer->id]);

            // Hämta servicesData, service_quantity, och comments
            $servicesData = session('servicesData');
            $service_quantity = session('service_quantity');
            $comments = session('comments');

            $has_material = (array) session('has_material', []);

            // Loopa genom servicesData
            foreach ($servicesData as $serviceId => $service) {
                $service_options = (isset($service['options'])) ? json_encode($service['options']) : null;

                $bookingServiceData = [
                    'booking_id' => $booking->id,
                    'service_id' => $serviceId,
                    'service_options' => $service_options,
                    'quantity' => isset($service_quantity[$serviceId]) ? $service_quantity[$serviceId] : 1,
                    'has_own_materials' => array_key_exists($serviceId, $has_material) ? 1 : 0,
                    'comments' => isset($comments[$serviceId]) ? $comments[$serviceId] : null,
                ];

                BookingService::create($bookingServiceData);
            }
        });

        session()->flush();

        return redirect()->route('home')->with('status', 'Bokning skapad!');
    }

    private function computeBookingData()
    {
        $import_services = session('services');
        $import_options = session('options');
        $import_service_quantity = session('service_quantity');
        $import_has_material = session('has_material');

        $servicesData = [];
        $serviceOptions = collect();
        $serviceHasMaterial = collect();

        $totalPrice = 0;
        $totalDuration = 0;  // Step 1: Initialisera totalDuration till 0

        if ($import_options) {
            foreach ($import_options as $option) {
                $serviceOptions->push(ServiceOption::where('id', $option)->first());
            }
        }

        list($rutPrice, $rotPrice, $nonRotRutPrice) = $this->computeServicePrices($import_services, $import_service_quantity, $import_options, $import_has_material);

        foreach ($import_services as $service) {
            $get_service = Service::where('id', $service)->first();
            $quantity = $import_service_quantity[$service] ?? 1;

            $totalDuration += $get_service->duration * $quantity;

            // Lägg till tjänstens pris till approximatePrice
            if ($get_service->price > 0) {
                $approximatePrice += $get_service->price * $quantity;
            }

            $servicePrice = round($get_service->price * $quantity);
            $materialPrice = in_array($service, $import_has_material ?? [], true) ? 0 : round($get_service->material_price * $quantity);

            $totalPrice += round($servicePrice + $materialPrice); // Lägg till detta

            foreach ($serviceOptions->where('service_id', $service) as $option) {
                $optionQuantity = session('option_quantity')[$option->id] ?? 1;
                $option->quantity = $optionQuantity;

                // Lägg till tjänstoptionens estimated_minutes till totalDuration
                if (isset($option->estimated_minutes)) {
                    $totalDuration += $option->estimated_minutes * $optionQuantity;
                }

                if (isset($option->price)) {
                    if (!in_array($service, $import_has_material ?? [], true)) {
                        $totalPrice += round($option->price * $quantity);
                    }
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

        // Totalpriset för tjänsterna (utan resetillägg)
        $servicePriceWithoutTravel = $this->computeTotalServicePrice($rutPrice, $rotPrice, $nonRotRutPrice);

        $approximatePrice = ($totalDuration / 60) * 200;  // Beräkna det ungefärliga priset

        session(['approximatePrice' => $approximatePrice]);  // Spara det i sessionen för framtida referens
        session(['price' => $servicePriceWithoutTravel]);
        session(['expected_time' => $totalDuration]);
        session(['servicesData' => $servicesData]);

        $containsROT = $rotPrice > 0;
        $containsRUT = $rutPrice > 0;
        $containsNonRUT = $nonRotRutPrice > 0;

        $hasMixed = ($containsRUT + $containsNonRUT + $containsROT) > 1;


        return compact('servicesData', 'totalPrice', 'hasMixed', 'approximatePrice');
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
            $materialPrice = in_array($service, $hasMaterials ?? [], true) ? 0 : round($get_service->material_price * $quantity);
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

        // Hämta den tidigare sparade datan från sessionen
        $servicesData = session('servicesData');

        // Spara all relevant data i sessionen
        session(['comments' => $comments, 'servicesData' => $servicesData]);

        // Skicka användaren till steg 3
        return redirect()->route('booking.step3');
    }


    public function showStep3()
    {
        $data = $this->computeBookingData();
        return view('booking.step3', $data);
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

    public function checkDate(Request $request)
    {
        $missionsToday = $this->getMissionsForDate($request->date, $request->order_id);
        $currentMissionDuration = $this->getCurrentMissionDuration();

        $startOfDay = Carbon::parse($request->date . ' ' . '09:00')->tz('Europe/Stockholm');
        $endOfDay = Carbon::parse($request->date . ' ' . '16:00')->tz('Europe/Stockholm')->subMinutes($currentMissionDuration);

        $busyTimes = $this->getBusyTimes($missionsToday, $startOfDay, $endOfDay, $currentMissionDuration);
        $freeTimes = $this->getFreeTimes($startOfDay, $endOfDay, $busyTimes);

        return response()->json($freeTimes);
    }

    private function getMissionsForDate($date, $excludeOrderId)
    {
        $missions = Booking::whereIn('status', ['1', '2'])
            ->where('order_id', '!=', $excludeOrderId)
            ->get();

        return $missions->filter(function ($mission) use ($date) {
            return Carbon::parse($mission->date)->tz('Europe/Stockholm')->isSameDay($date);
        });
    }

    private function getCurrentMissionDuration()
    {
        $travelTime = session('travel_duration');
        $extraMinutes = ($travelTime * 2) + 15;

        $expectedEndTime = Carbon::now()->addMinutes(max(session('expectedTime'), 60))->addMinutes($extraMinutes);
        return $expectedEndTime->diffInMinutes(Carbon::now());
    }

    private function getBusyTimes($missionsToday, $startOfDay, $endOfDay, $currentMissionDuration)
    {
        $busyTimes = collect();

        foreach ($missionsToday as $mission) {
            $missionStart = $this->getMissionStart($mission, $startOfDay);
            $missionEnd = $this->getMissionEnd($mission, $currentMissionDuration);

            $busyPeriod = new CarbonPeriod($missionStart, $missionEnd);

            foreach ($busyPeriod->dateInterval as $busyTime) {
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
        $travelTime = Customers::where('order_id', $mission->order_id)->first()->travel_time;
        $missionStart = Carbon::parse($mission->date)->subMinutes($travelTime + 15)->tz('Europe/Stockholm');

        return $missionStart->lt($startOfDay) ? $startOfDay : $missionStart;
    }

    private function getMissionEnd($mission, $currentMissionDuration)
    {
        $travelTime = Customers::where('order_id', $mission->order_id)->first()->travel_time;

        return Carbon::parse($mission->date)
            ->addSeconds(max($mission->expected_time, 3600))
            ->addMinutes($travelTime + 15 + $currentMissionDuration)
            ->tz('Europe/Stockholm');
    }

}
