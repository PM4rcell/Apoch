<?php

namespace Database\Factories;

use App\Models\Era;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\cinema>
 */
class CinemaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'era_id' => Era::factory(),
            'name' => fake()->word(),
            'city' =>fake()->city(),
            'address' =>fake()->address(),
        ];
    }
}
