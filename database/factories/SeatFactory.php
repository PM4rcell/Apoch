<?php

namespace Database\Factories;

use App\Models\Auditorium;
use App\Models\Seat_Type;
use App\Models\SeatType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Seat>
 */
class SeatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'auditorium_id' => Auditorium::query()->inRandomOrder()->first()->id,
            'seat_type_id' => SeatType::factory(),
            'number' => fake()->numberBetween(1, 20),
            'row' => fake()->numberBetween(1, 14),
        ];
    }
}
