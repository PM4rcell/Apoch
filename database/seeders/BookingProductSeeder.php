<?php

namespace Database\Seeders;

use App\Models\Booking_product;
use App\Models\BookingProduct;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookingProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BookingProduct::factory(6)->create();
    }
}
