<?php

use App\Models\Booking;
use App\Models\ParkingSpace;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\travel;
uses(RefreshDatabase::class);

it('prevent updating when date range is already taken', function () {

    $this->seed();

    $this->actingAs(User::first());

    $data = [
        'date_from' => now()->addDays(1)->format('Y-m-d H:i:s'),
        'date_to' => now()->addDays(2)->format('Y-m-d H:i:s'),
        'parking_space_id' => ParkingSpace::first()->id,
    ];

    $response = $this->postJson(route('api.book.parking.space'), $data);

    $response->assertStatus(201);

    $data = [
        'date_from' => now()->addDays(3)->format('Y-m-d H:i:s'),
        'date_to' => now()->addDays(5)->format('Y-m-d H:i:s'),
        'parking_space_id' => ParkingSpace::first()->id,
    ];

    $response = $this->postJson(route('api.book.parking.space'), $data);

    $response->assertStatus(201);

    $booking = Booking::first();

    $data = [
        'date_from' => $booking->date_from,
        'date_to' => now()->addDays(3)->format('Y-m-d H:i:s'),
        'parking_space_id' => ParkingSpace::first()->id,
    ];

    $response = $this->patchJson(route('api.update.bookings', ['booking' => $booking->id]), $data);

    $response->assertStatus(422);
    

});

it('can update if parking space other date is not taken', function () {

    $this->seed();

    $this->actingAs(User::first());

    $data = [
        'date_from' => now()->addDays(1)->format('Y-m-d H:i:s'),
        'date_to' => now()->addDays(2)->format('Y-m-d H:i:s'),
        'parking_space_id' => ParkingSpace::first()->id,
    ];

    $response = $this->postJson(route('api.book.parking.space'), $data);

    $booking = Booking::first();

    $data = [
        'date_from' => $booking->date_from,
        'date_to' => now()->addDays(3)->format('Y-m-d H:i:s'),
        'parking_space_id' => ParkingSpace::first()->id,
    ];

    $response = $this->patchJson(route('api.update.bookings', ['booking' => $booking->id]), $data);

    $response->assertStatus(201);
    

});


