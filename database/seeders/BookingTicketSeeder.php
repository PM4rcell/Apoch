<?php

namespace Database\Seeders;

use App\Models\Booking_ticket;
use App\Models\BookingTicket;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookingTicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BookingTicket::factory(10)->create();
    }
}
