<?php

use App\Models\ParkingSpace;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
 
uses(RefreshDatabase::class);

test('prevent overlaping date range bookings in one parking space', function () {

    $this->seed();

    $this->actingAs(User::first());

    $data = [
        'date_from' => now()->addDays(1)->format('Y-m-d H:i:s'),
        'date_to' => now()->addDays(2)->format('Y-m-d H:i:s'),
        'parking_space_id' => ParkingSpace::first()->id,
    ];

    $response = $this->postJson(route('api.book.parking.space'), $data);

    $response->assertStatus(201);

    $response = $this->postJson(route('api.book.parking.space'), $data);

    $response->assertStatus(422);

});

test('date should be greater or equal than today validation booking', function () {

    $this->seed();

    $this->actingAs(User::first());

    $data = [
        'date_from' => now()->addDays(-1)->format('Y-m-d H:i:s'),
        'date_to' => now()->addDays(2)->format('Y-m-d H:i:s'),
        'parking_space_id' => ParkingSpace::first()->id,
    ];


    $response = $this->postJson(route('api.book.parking.space'), $data);

    $response->assertStatus(422);

    $data = [
        'date_from' => now()->format('Y-m-d H:i:s'),
        'date_to' => now()->addDays(2)->format('Y-m-d H:i:s'),
        'parking_space_id' => ParkingSpace::first()->id,
    ];


    $response = $this->postJson(route('api.book.parking.space'), $data);

    $response->assertStatus(201);
});
