<?php

use App\Models\ParkingSpace;
use App\Models\Price;
use App\Models\DailyPrice;
use Illuminate\Support\Carbon;
use Illuminate\Support\CarbonPeriod;

use Illuminate\Foundation\Testing\RefreshDatabase;
 
uses(RefreshDatabase::class);

beforeEach(function () {
    $this->parkingSpace = ParkingSpace::factory()->create();
});

it('calculates price using default price only', function () {

    
    $parkingSpace = ParkingSpace::factory()->create([
        'length' => 5,
        'width' => 2,
        'is_covered' => true,
        'has_ev_charger' => false,
    ]);

    $defaultPrice = Price::factory()->create([
        'is_default' => true,
        'title' => 'Default',
        'valid_from' => null,
        'valid_until' => null,
    ]);
  
    foreach (['monday', 'tuesday', 'wednesday'] as $day) {
        DailyPrice::factory()->create([
            'price_id' => $defaultPrice->id,
            'day' => $day,
            'price' => 10.00,
        ]);
    }
   
    $parkingSpace->prices()->attach([$defaultPrice->id]);

    $result = $parkingSpace->calculatePriceForPeriod(
        now()->next('Monday')->toDateString(),
        now()->next('Wednesday')->toDateString()
    );

    expect($result['total'])->toBe(30.00);
    expect($result['details'])->toHaveCount(3);
});

it('uses seasonal price when applicable', function () {

    $parkingSpace = ParkingSpace::factory()->create([
        'length' => 5,
        'width' => 2,
        'is_covered' => true,
        'has_ev_charger' => false,
    ]);

    $defaultPrice = Price::factory()->create([
        'is_default' => true,
        'title' => 'Default',
        'valid_from' => null,
        'valid_until' => null,
    ]);

    $season = Price::factory()->create([
        'is_default' => false,
        'title' => 'Summer Sale',
        'valid_from' => now()->toDateString(),
        'valid_until' => now()->addDays(2)->toDateString(),
    ]);

    DailyPrice::factory()->create([
        'price_id' => $defaultPrice->id,
        'day' => 'monday',
        'price' => 8.00,
    ]);

    foreach (['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day) {
        DailyPrice::factory()->create([
            'price_id' => $season->id,
            'day' => $day,
            'price' => 5.00,
        ]);
    }

    $parkingSpace->prices()->attach([$defaultPrice->id, $season->id]);

    $result = $parkingSpace->calculatePriceForPeriod(
        now()->toDateString(),
        now()->toDateString()
    );

    expect($result['total'])->toBe(5.00);
    expect($result['details'][0]['season'])->toBe('Summer Sale');
});

it('Return exception if no default prices has found', function () {
    $defaultPrice = Price::factory()->create([
        'is_default' => true,
        'title' => 'Default',
        'valid_from' => null,
        'valid_until' => null,
    ]);

    
    expect(fn() => $this->parkingSpace->calculatePriceForPeriod(
        now()->toDateString(),
        now()->addDay()->toDateString()
    ))->toThrow(Exception::class, 'No prices found for the parking space.');
});

