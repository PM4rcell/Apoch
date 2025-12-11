<?php

namespace Database\Factories;

use App\Models\cinema;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Auditorium>
 */
class AuditoriumFactory extends Factory
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
            'cinema_id' => cinema::query()->inRandomOrder()->first()->id,
            'capacity' => fake()->numberBetween(15,50)
        ];
    }
}
