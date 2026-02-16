<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TicketType>
 */
class TicketTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['default', 'student']),
            'price' => fake()->randomFloat(2, 5, 30),
            'point_price' => fake()->numberBetween(100, 10000),
            'start_date' => fake()->date(),
            'end_date' => fake()->date(),
        ];
    }
}
