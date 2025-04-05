<?php

namespace Database\Factories;

use App\Models\ParkingSpace;
use App\Models\Price;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ParkingSpace>
 */
class ParkingSpaceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Parking Space ' . ParkingSpace::count() + 1,
            'length' => $this->faker->randomFloat(2, 1, 50),
            'width' => $this->faker->randomFloat(2, 1, 50),
            'has_ev_charger' => $this->faker->boolean(), 
            'is_covered' => $this->faker->boolean(),
            'notes' => now(),
        ];
    }

    public function withPrices()
    {
        return $this->afterCreating(function (ParkingSpace $parkingSpace)  {

            $parkingSpace->prices()->attach(
            Price::pluck('id')->toArray(),
            );

        });
    }
}