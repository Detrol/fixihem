<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Category;
use App\Models\Customers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use ReflectionClass;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $categories = Category::with('services', 'services.service_options')->where('active', 1)->get();

        session()->forget(['services', 'options', 'service_quantity', 'has_material', 'option_quantity', 'toralPrice', 'price', 'expected_time', 'expectedTime', 'serviceData', 'comments', 'travels']);

        return view('home', compact('categories'));
    }

    public function my_calendar(Request $request)
    {
        $customer_bookings = Booking::whereIn('status', [1, 2, 3, 4, 5])->get();

        $entries = array();

        foreach ($customer_bookings as $booking) {
            $mission_date = substr($booking->date_time, 0, 10);
            $mission_time = substr(substr($booking->date_time, 11, 18), 0, 5);

            $customer_details = Customers::where('order_id', $booking->order_id)->first();

            $event = Event::create('Bokning')
                ->description($customer_details->first_name . ' ' . $customer_details->last_name)
                ->uniqueIdentifier($booking->order_id)
                ->createdAt($booking->created_at)
                ->startsAt(Carbon::parse($mission_date . ' ' . $mission_time))
                ->endsAt(Carbon::parse($mission_date . ' ' . $mission_time)->addMinutes(max($booking->expected_time, 60)))
                ->address($customer_details->address . ' ' . $customer_details->city);

            $entries[] = $event;
        }

        $jsonString = file_get_contents('https://content.googleapis.com/calendar/v3/calendars/sv.swedish%23holiday%40group.v.calendar.google.com/events?key=AIzaSyAz9ZZf4KGBMl-zdh4MBJri6k-3E6VPCgY');
        $holidays = json_decode($jsonString);

        foreach ($holidays->items as $holiday) {
            $event = Event::create($holiday->summary)
                ->description($holiday->description)
                ->startsAt(Carbon::parse($holiday->start->date . ' ' . '06:00'))
                ->endsAt(Carbon::parse($holiday->start->date . ' ' . '18:00'))
                ->fullDay();

            $entries[] = $event;
        }

        $calendar = Calendar::create('Fixihem')->event($entries)->refreshInterval(5);

        return response($calendar->get())
            ->header('Content-Type', 'text/calendar; charset=utf-8');
    }
}
