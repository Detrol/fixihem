<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Customers;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $orders = Booking::whereDay('date', '=', date('d'))
            ->whereMonth('date', '=', date('m'))
            ->where('status', 2)
            ->get()
            ->sortBy('date');

        $order_details = [];

        foreach ($orders as $order) {
            $customer_details = Customers::where('order_id', $order->order_id)->first();

            $order_details[$order->id]['order_id'] = $order->order_id;
            $order_details[$order->id]['name'] = $customer_details->first_name . ' ' . $customer_details->last_name;
            $order_details[$order->id]['address'] = $customer_details->address;
            $order_details[$order->id]['date'] = Carbon::parse($order->date)->format('d/m-y - H:i');
            $order_details[$order->id]['distance_price'] = $customer_details->distance_price;
            $order_details[$order->id]['travel_time'] = CarbonInterval::minutes($customer_details->travel_time)->cascade();
            $order_details[$order->id]['expected_time'] = gmdate("H:i:s", $order->expected_time);
            $order_details[$order->id]['worked_time'] = Carbon::parse($order->start_time)->diffAsCarbonInterval($order->stop_time)->format('%H:%I:%S');
            $order_details[$order->id]['start'] = $order->start_time;
            $order_details[$order->id]['stop'] = $order->stop_time;
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
        $bookings = Booking::where('status', $request->status)->orderBy('date_time')->get();
        $title = null;

        $order_details = [];
        foreach ($bookings as $order) {
            $customer_details = Customers::where('order_id', $order->order_id)->first();

            $order_details[$order->id]['order_id'] = $order->order_id;
            $order_details[$order->id]['address'] = $customer_details->address;
            $order_details[$order->id]['name'] = $customer_details->first_name . ' ' . $customer_details->last_name;
            $order_details[$order->id]['date'] = Carbon::parse($order->date_time)->format('d/m-y - H:i');
            $order_details[$order->id]['price'] = $order->total_price;
            $order_details[$order->id]['paid'] = $order->total_price * ((100 - 6 - 12.22) / 100);
            $order_details[$order->id]['distance_price'] = $customer_details->distance_price;
            $order_details[$order->id]['travel_time'] = CarbonInterval::minutes($customer_details->travel_time)->cascade();
            $order_details[$order->id]['expected_time'] = gmdate("H:i:s", $order->expected_time);
            $order_details[$order->id]['worked_time'] = Carbon::parse($order->start_time)->diffAsCarbonInterval($order->stop_time)->format('%H:%I:%S');
            $order_details[$order->id]['start'] = $order->start_time;
            $order_details[$order->id]['stop'] = $order->stop_time;
            $order_details[$order->id]['status'] = $order->status;
            $order_details[$order->id]['spontaneous'] = $order->spontaneous;
        }

        $earning = 0;
        $earning_before = 0;
        foreach ($bookings as $booking) {
            if ($booking->customer_type === 1) {
                $earning += $booking->total_price * ((100 - 6 - 12.22) / 100);
            } else {
                $earning += $booking->total_price * ((100 - 6 - 42.89) / 100);
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
        $order->stop_time = Carbon::now();
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
            $service->service_options_array = json_decode($service->service->service_options, true);
        }

        return view('admin.ajax.order_details', compact('order', 'customer_details'));
    }

    public function order_text(Request $request)
    {
        $order = CustomerBookings::where('order_id', $request->order_id)->first();
        if ($order->user_id) {
            $customer_details = CustomerDetailsUsers::where('user_id', $order->user_id)->first();
        } else {
            $customer_details = CustomerDetails::where('order_id', $order->order_id)->first();
        }
        $get_categories_all = DataServiceCategories::where('active', 1)->get();
        $get_u_categories_all = DataServiceUCategories::where('active', 1)->get();
        $get_special_services = CustomerSpecialServices::where('order_id', $request->order_id)->get();
        $worked_time = $request->worked_time;

        return view('admin.ajax.order_text', compact('order', 'customer_details', 'get_categories_all', 'get_u_categories_all', 'get_special_services', 'worked_time'));
    }

    public function generateInvoiceText($orderId)
    {
        $order = Booking::with(['services', 'services.service'])->where('order_id', $orderId)->first();

        $rutServices = [];
        $rotServices = [];
        $otherServices = [];

        foreach ($order->services as $service) {
            if ($service->service->is_rut) {
                $rutServices[] = $service;
            } elseif ($service->service->is_rot) {
                $rotServices[] = $service;
            } else {
                $otherServices[] = $service;
            }
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

        $total = 0;
        foreach ($order->services as $service) {
            $total += $service->service->price * $service->quantity;
            if(is_array($service->service_options) && !empty($service->service_options)) {
                foreach($service->service_options as $option) {
                    $total += $option['price'] * $service->quantity;
                }
            }
        }

        $total += $travelToCustomer['price'];

        if($travelToLocation) {
            $total += $travelToLocation['total_price'];
        }

        return view('admin.ajax.invoice_text', compact('order', 'rutServices', 'rotServices', 'otherServices', 'travelToCustomer', 'travelToLocation', 'total'));
    }



}
