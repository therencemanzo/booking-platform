<?php

use App\Models\Booking;
use App\Models\ParkingSpace;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('prevents race condition by using a lock during concurrent bookings', function () {

    $this->seed();

    $user = User::factory()->create();
    $this->actingAs($user);
    // Create a parking space to test with
    $parkingSpace = ParkingSpace::first();

    // Define the booking data (same for all users)
    $dateFrom = now()->addDay()->format('Y-m-d H:i:s');
    $dateTo = now()->addDay(2)->format('Y-m-d H:i:s');
    $bookingData = [
        'parking_space_id' => $parkingSpace->id,
        'date_from' => $dateFrom,
        'date_to' => $dateTo,
    ];

    // Simulate 10 concurrent requests to book the same parking space
    $responses = [];
    $threads = 20 ;

    // Run 10 concurrent requests (simulated via promises)
    for ($i = 0; $i < $threads; $i++) {
        $responses[] = $this->postJson(route('api.book.parking.space'), $bookingData);
    }

    // Now check the responses, ensuring only one booking was successful
    
    $successCount = 0;
    $failureCount = 0;

    foreach ($responses as $response) {

        if($response->status() === 201) {
            $successCount++;
        } else{
          $failureCount++;
        }
        
    }
  
    // Assert that only one booking was successful and the others failed due to the lock
    expect($successCount)->toBe(1);
    expect($failureCount)->toBe($threads - 1);
});
