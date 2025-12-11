<?php

namespace Database\Seeders;

use App\Models\Auditorium;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AuditoriumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Auditorium::factory(10)->create();
    }
}
