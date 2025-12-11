<?php

namespace Database\Factories;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [            
            'booking_id' => Booking::query()->inRandomOrder()->first()->id,
            'amount' => fake()->randomFloat(2,30,200),            
            'method' => fake()->randomElement(['Pending', 'Paid', 'Processing']),
            'status' => fake()->randomElement(['Cash', 'Card'])
        ];
    }
}
