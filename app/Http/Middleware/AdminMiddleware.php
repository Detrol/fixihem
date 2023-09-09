<?php

namespace App\Http\Middleware;

use App\Models\Booking;
use App\Models\CustomerBookings;
use App\Models\CustomerPriceRequests;
use App\Models\CustomerVisits;
use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $bookings = Booking::all();
        $today = Booking::whereDay('date_time', '=', date('d'))->whereMonth('date_time', '=', date('m'))->where('status', 2)->get()->sortBy('date_time');

        $today_count = count($today);

        $request->merge(compact(
            'bookings',
            'today',
            'today_count',
        ));

        return $next($request);
    }
}
