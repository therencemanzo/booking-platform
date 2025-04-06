<?php

use App\Http\Controllers\API\v1\AuthController;
use App\Http\Controllers\API\v1\BookingController;
use App\Http\Controllers\API\v1\ParkingSpacesController;
use App\Http\Controllers\API\v1\PriceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::prefix('v1')->group(function () {

    //auth routes
    Route::post('/login', [AuthController::class, 'login'])->name('api.login');

    Route::get('/get-prices', [PriceController::class, 'getPricesBySeason'])->name('api.get.prices.by.season');

    Route::middleware('auth:sanctum')->group(function () {

        Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');

        Route::get('/parking-spaces', [ParkingSpacesController::class, 'getParkingSpaces'])->name('api.get.parking.spaces');
        Route::get('/parking-space/{parkingSpace}', [ParkingSpacesController::class, 'getParkingSpaceDetails'])->name('api.get.parking.space.details');
        Route::get('/parking-space-price-details/{parkingSpace}', [ParkingSpacesController::class, 'getParkingSpacePriceDetails'])->name('api.get.parking.space.price.details');
        Route::post('/parking-spaces/book', [BookingController::class, 'bookParkingSpace'])->name('api.book.parking.space');
        Route::get('/bookings', [BookingController::class, 'getBookings'])->name('api.get.bookings');
        
        //has policy to ensure only the customer can view the details of its booking
        Route::get('/booking/{booking}', [BookingController::class, 'getBookingDetails'])->name('api.get.booking.details')->middleware('can:view,booking');

        //has policy to insure the user can only update their own bookings, cancel only active bookings and cannot update or cancel bookings that are already started
        Route::patch('/booking/{booking}', [BookingController::class, 'updateBooking'])->name('api.update.bookings')->middleware('can:update,booking');
        Route::patch('/cancel-booking/{booking}', [BookingController::class, 'cancelBooking'])->name('api.cancel.bookings')->middleware('can:cancel,booking');

    });
   

});