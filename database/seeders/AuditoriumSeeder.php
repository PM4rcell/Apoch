<?php

namespace Database\Seeders;

use App\Models\Auditorium;
use App\Models\Seat;
use App\Models\SeatType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AuditoriumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     */
    public function run(): void
    {
        $auditoria = Auditorium::factory(5)->create([
            "capacity" => 96
        ]);

        foreach ($auditoria as $auditorium) {
            for ($row = 1; $row <= 8; $row++) {
               for ($col = 1; $col <= 12; $col++) {
                    Seat::factory()->create([
                        'auditorium_id' => $auditorium->id,
                        'row'           => $row,
                        'number'        => $col,
                    ]);
                }
            }
        }
    }
}
