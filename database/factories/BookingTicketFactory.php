<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Ticket_type;
use App\Models\TicketType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BookingTicket>
 */
class BookingTicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [     
            'ticket_type_id' => TicketType::query()->inRandomOrder()->first()->id,
            'booking_id' => Booking::query()->inRandomOrder()->first()->id,
            'quantity' => fake()->numberBetween(1,6)
        ];
    }
}
