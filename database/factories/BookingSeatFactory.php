<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Seat;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BookingSeat>
 */
class BookingSeatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'seat_id' => Seat::query()->inRandomOrder()->first()->id,
            'booking_id' => Booking::query()->inRandomOrder()->first()->id,
        ];
    }
}
