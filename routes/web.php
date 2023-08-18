<?php

use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['web']], function() {
    Route::post('/steg1', [BookingController::class, 'processStep1'])->name('booking.step1');
    Route::get('/steg2', [BookingController::class, 'showStep2'])->name('booking.step2');
    Route::post('/step2', [BookingController::class, 'processStep2'])->name('booking.processStep2');
    Route::get('/step3', [BookingController::class, 'showStep3'])->name('booking.step3');

    Route::post('/get-distance-from-origin-to-customer', [BookingController::class, 'getDistanceFromOriginToCustomer'])->name('getDistanceFromOriginToCustomer');
    Route::post('/get-nearest-drive-location', [BookingController::class, 'getNearestDriveLocation'])->name('getNearestDriveLocation');

    Route::post('/save-address-to-session', [BookingController::class, 'saveAddressToSession']);
    Route::get('/get-address-from-session', [BookingController::class, 'getAddressFromSession']);

    Route::post('/save-recycling-info-to-session', [BookingController::class, 'saveRecyclingInfo']);
    Route::get('/get-recycling-info-from-session', [BookingController::class, 'getRecyclingInfo']);

    Route::get('/check-date', [BookingController::class, 'checkDate'])->name('check_date');

    Route::post('/store', [BookingController::class, 'store'])->name('booking.store');

});



