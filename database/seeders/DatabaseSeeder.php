<?php

namespace Database\Seeders;

use App\Models\ParkingSpace;
use App\Models\Price;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //User::factory(10)->create(['role' => 'customer']);

        $summePrice = Price::factory()->withDailyPrices()->create([
            'title' => 'Summer Season Prices',
            'valid_from' => '2025-04-04',
            'valid_until' => '2025-10-04',
            'is_default' => false,
        ]);

        $winterPrice = Price::factory()->withDailyPrices()->create([
            'title' => 'Winter Season Prices',
            'valid_from' => '2025-10-05',
            'valid_until' => '2026-04-04',
            'is_default' => false,
        ]);

        $defaultPrice = Price::factory()->withDailyPrices()->create([
            'title' =>  'Default Prices',
            'valid_from' => null,
            'valid_until' => null,
        ]);

        User::factory()->create([
            'name' => 'Customer User',
            'email' => 'customer@example.com',
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'admin',
        ]);

        //Loop to create 10 parking spaces with correct count
        for ($i = 0; $i < 10; $i++) {
            ParkingSpace::factory()->withPrices()->create();
        }
        

    }
}
