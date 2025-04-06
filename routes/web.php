<?php

use App\Http\Controllers\BookingController;
use App\Jobs\SendAdminNotification;
use App\Models\Booking;
use App\Models\Price;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('/test', function () {
    $booking = Booking::latest()->first();
    SendAdminNotification::dispatch($booking);
});

Route::get('dashboard', [BookingController::class,'index'])->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
