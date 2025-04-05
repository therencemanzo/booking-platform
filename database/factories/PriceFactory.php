<?php

namespace Database\Factories;

use App\Models\DailyPrice;
use App\Models\Price;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Price>
 */
class PriceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $seasion = $this->faker->randomElement(['summer', 'winter', 'spring', 'autumn']);

        return [
            'title' => $seasion,
            'valid_from' => $this->faker->dateTimeBetween('-1 year', '+1 year'),
            'valid_until' => $this->faker->dateTimeBetween('+1 year', '+2 years'),
        ];
    }


    public function withDailyPrices()
    {
        return $this->afterCreating(function (Price $price) {
            DailyPrice::factory()->count(3)->create([
                'price_id' => $price->id,
                'day' => 'monday',
                'price' => 8.00,
            ]);
            DailyPrice::factory()->count(3)->create([
                'price_id' => $price->id,
                'day' => 'tuesday',
                'price' => 8.00,
            ]);
            DailyPrice::factory()->count(3)->create([
                'price_id' => $price->id,
                'day' => 'wednesday',
                'price' => 8.00,
            ]);
            DailyPrice::factory()->count(3)->create([
                'price_id' => $price->id,
                'day' => 'thursday',
                'price' => 8.00,
            ]);
            DailyPrice::factory()->count(3)->create([
                'price_id' => $price->id,
                'day' => 'friday',
                'price' => 8.00,
            ]);
            DailyPrice::factory()->count(3)->create([
                'price_id' => $price->id,
                'day' => 'saturday',
                'price' => 10.00,
            ]);
            DailyPrice::factory()->count(3)->create([
                'price_id' => $price->id,
                'day' => 'sunday',
                'price' => 10.00,
            ]);
        });
    }
}
