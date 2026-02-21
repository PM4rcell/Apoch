<?php

namespace Database\Seeders;

use App\Models\Screening;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScreeningSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Screening::factory(10)->create();
        Screening::factory()->create([
            "auditorium_id" => 1,
            'language_id' => 1,
            'movie_id' => 1,
            'screening_type_id' => 1,
            'start_time' => "2026-02-19 12:30:00"
        ]);
    }    
}
