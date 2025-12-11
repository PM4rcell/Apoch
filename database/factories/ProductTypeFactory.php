<?php

namespace Database\Factories;

use App\Models\Era;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductType>
 */
class ProductTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'price' => fake()->randomFloat(2, 5,100),
            'point_price' => fake()->numberBetween(100, 5000),
            'era_id' => Era::query()->inRandomOrder()->first()->id,
            'start_date' => fake()->date(),
            'end_date' => fake()->date(),
        ];
    }
}
