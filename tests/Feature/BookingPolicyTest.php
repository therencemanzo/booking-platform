<?php

use App\Models\User;
use App\Models\Booking;
use App\Models\ParkingSpace;
use Illuminate\Support\Carbon;
use Illuminate\Auth\Access\Response;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\travel;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
   
    $this->seed();
    $this->user = User::first();
    $this->otherUser =  User::where('id','<>', $this->user->id)->first();
    $this->parkingSpace = ParkingSpace::first();

    $dateTo = now()->addDays(2);
    $dateFrom = now();
    $price = $this->parkingSpace->calculatePriceForPeriod($dateFrom, $dateTo);
    
    $this->booking = Booking::factory()->create([
        'customer_id' => $this->user->id,
        'parking_space_id' => $this->parkingSpace->id,
        'status' => 'active',
        'total_price' => $price['total'],
        'details' => json_encode($price['details']),
        'date_to' => now()->addDay(),
        'date_from' => now(), // future booking
    ]);

    $this->cancellBooking = Booking::factory()->create([
        'customer_id' => $this->user->id,
        'parking_space_id' => $this->parkingSpace->id,
        'status' => 'cancelled',
        'total_price' => $price['total'],
        'details' => json_encode($price['details']),
        'date_to' => now()->addDay(),
        'date_from' => now(), // future booking
    ]);

    $this->lateBooking = Booking::factory()->create([
        'customer_id' => $this->user->id,
        'parking_space_id' => $this->parkingSpace->id,
        'status' => 'active',
        'total_price' => $price['total'],
        'details' => json_encode($price['details']),
        'date_to' => now()->addDay(),
        'date_from' => now(), // future booking
        'created_at' => now()->subMinutes(80),
    ]);

    $this->policy = new \App\Policies\BookingPolicy();
});

it('allows the owner to update an active booking within grace period and in the future', function () {

    $result = $this->policy->update($this->user, $this->booking);
    expect($result)->toBeInstanceOf(Response::class)
        ->and($result->allowed())->toBeTrue();
});

it('denies update if user is not the owner and booking is in the future', function () {
   

    $result = $this->policy->update($this->otherUser, $this->booking);
    expect($result->denied())->toBeTrue()
        ->and($result->message())->toBe('You do not own this booking.');
});

it('denies update if booking is cancelled', function () {
   

    $result = $this->policy->update($this->user, $this->cancellBooking);
    
    expect($result->denied())->toBeTrue()
        ->and($result->message())->toBe('Booking was already cancelled.');
});

it('denies update if more than 60 minutes have passed since creation', function () {
    
    $result = $this->policy->update($this->user, $this->lateBooking);
    
    expect($result->denied())->toBeTrue()
        ->and($result->message())->toBe('Cannot update Booking update/cancel grace period is finished and booking was already started.');
});
