<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
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

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/c/d289skj2', [HomeController::class, 'my_calendar']);

Route::post('/form_mail', [HomeController::class, 'form_mail'])->name('form_mail');

Route::view('/terms', 'terms')->name('terms');
Route::view('/privacy', 'privacy')->name('privacy');
Route::view('/cookies', 'cookies')->name('cookies');

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

Route::get('/admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login.form');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login');

Route::prefix('admin')->middleware('is.admin', 'AdminMiddleware')->group(function () {
    Route::get('/', [AdminController::class, 'index']);
    Route::get('today', [AdminController::class, 'today'])->name('today');
    Route::get('reserved', [AdminController::class, 'reserved'])->name('reserved');
    Route::get('bookings/{status}', [AdminController::class, 'bookings'])->name('bookings');

    Route::get('order_details', [AdminController::class, 'order_details'])->name('order_details');
    Route::get('order/{order}/invoice-text', [AdminController::class, 'generateInvoiceText'])->name('admin.order.invoice-text');

    Route::get('order_edit/{order_id}', [AdminController::class, 'order_edit'])->name('order_edit');
    Route::post('order_edit_submit/{order_id}', [AdminController::class, 'order_edit_submit'])->name('order_edit_submit');

    Route::get('order_accept/{order_id}', [AdminController::class, 'order_accept'])->name('order_accept');
    Route::get('order_abort/{order_id}', [AdminController::class, 'order_abort'])->name('order_abort');
    Route::get('order_completed/{order_id}', [AdminController::class, 'order_completed'])->name('order_completed');
    Route::get('order_invoiced/{order_id}', [AdminController::class, 'order_invoiced'])->name('order_invoiced');
    Route::get('order_paid/{order_id}', [AdminController::class, 'order_paid'])->name('order_paid');
    Route::get('order_pause/{order_id}', [AdminController::class, 'order_pause'])->name('order_pause');

    Route::get('order_start_time/{order_id}', [AdminController::class, 'order_start_time'])->name('order_start_time');
    Route::get('order_stop_time/{order_id}', [AdminController::class, 'order_stop_time'])->name('order_stop_time');

    Route::get('/order_text/{order_id}', [AdminController::class, 'order_text'])->name('order_text');

    Route::post('logout', [AdminController::class, 'logout'])->name('admin.logout');
});



