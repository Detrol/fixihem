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
            'expected_time' => session('expectedTime'),
            'total_price' => session('total_price'),
        ];

        $date = $request->input('date');
        $time = $request->input('time');
        $date_time = $date . ' ' . $time;

        $finalBookingData = array_merge(
            $bookingDataFromRequest,
            ['date_time' => $date_time],
            $sessionData
        );

        DB::transaction(function() use ($finalBookingData, $request) {
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

            // Loopa genom servicesData
            foreach ($servicesData as $serviceId => $service) {
                $service_options = (isset($service['options'])) ? json_encode($service['options']) : null;

                $bookingServiceData = [
                    'booking_id' => $booking->id,
                    'service_id' => $serviceId,
                    'service_options' => $service_options,
                    'quantity' => isset($service_quantity[$serviceId]) ? $service_quantity[$serviceId] : 1,
                    'has_own_materials' => $service['customer_materials'] == 'yes' ? 1 : 0,
                    'comments' => isset($comments[$serviceId]) ? $comments[$serviceId] : null
                ];

                BookingService::create($bookingServiceData);
            }
        });

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

        foreach ($import_services as $service) {
            $get_service = Service::where('id', $service)->first();
            $quantity = $import_service_quantity[$service] ?? 1;

            $totalDuration += $get_service->duration * $quantity;

            $servicePrice = round($get_service->price * $quantity);
            $materialPrice = in_array($service, $import_has_material ?? [], true) ? 0 : round($get_service->material_price * $quantity);

            foreach ($serviceOptions->where('service_id', $service) as $option) {
                if (isset($option->price)) {
                    if (!in_array($service, $import_has_material ?? [], true)) {
                        $totalPrice += round($option->price * $quantity);
                    }
                }
            }

            $totalPrice += round($servicePrice + $materialPrice);

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


        if ($totalPrice < 300) {
            $totalPrice = 300;
        }

        session(['total_price' => $totalPrice]);
        session(['expectedTime' => $totalDuration]);
        session(['servicesData' => $servicesData]);

        $rotStatuses = [];
        foreach ($import_services as $service) {
            $get_service = Service::where('id', $service)->first();
            $rotStatuses[] = $get_service->is_rot;
        }

        $containsROT = in_array(1, $rotStatuses, true);

        $rutStatuses = [];
        foreach ($import_services as $service) {
            $get_service = Service::where('id', $service)->first();
            $rutStatuses[] = $get_service->is_rut;
        }

        $containsRUT = in_array(1, $rutStatuses, true);
        $containsNonRUT = in_array(0, $rutStatuses, true);

        $hasMixed = ($containsRUT + $containsNonRUT + $containsROT) > 1;

        return compact('servicesData', 'totalPrice', 'hasMixed');
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

        // Spara distans och restid i sessionen
        session(['travel_distance' => $distanceInKilometers]);
        session(['travel_duration' => $durationInMinutes]);

        return response()->json(['distance' => $distanceInKilometers, 'duration' => $durationInMinutes]);
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
            if ($distanceInMeters < $shortestDistance) {
                $shortestDistance = $distanceInMeters;
                $nearestLocation = $location;
            }

            sleep(1); // Väntar i en sekund
        }

        return response()->json([
            'distance' => $shortestDistance / 1000,
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

        //dd($options);

        // Här kan du göra vad du behöver med datan, t.ex. lagra den i en session.
        session(compact('services', 'options', 'service_quantity', 'has_material'));

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

    public function saveAddressToSession(Request $request) {
        $request->session()->put('address', $request->input('address'));
        $request->session()->put('city', $request->input('city'));
        $request->session()->put('postal_code', $request->input('postal_code'));
        return response()->json(['status' => 'success']);
    }

    public function getAddressFromSession(Request $request) {
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
