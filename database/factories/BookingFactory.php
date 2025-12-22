<?php

namespace Database\Factories;

use App\Models\Profile;
use App\Models\Screening;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'screening_id' => Screening::query()->inRandomOrder()->first()->id,
            'user_id' => User::query()->inRandomOrder()->first()->id,
            'booking_fee' => fake()->randomFloat(2,0, 3),
            'email' => null,
            'status' => fake()->randomElement(['Watched', 'Upcoming'])
        ];
    }
}
