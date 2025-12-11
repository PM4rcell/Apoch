<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Product_type;
use App\Models\ProductType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BookingProduct>
 */
class BookingProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
             'product_type_id' => ProductType::query()->inRandomOrder()->first()->id,
            'booking_id' => Booking::query()->inRandomOrder()->first()->id,
            'quantity' => fake()->numberBetween(1,6)
        ];
    }
}
