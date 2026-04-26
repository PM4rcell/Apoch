<?php

namespace Database\Seeders;

use App\Models\ScreeningType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScreeningTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ScreeningType::create([
            "name" => "2D",
            "price_multiplier" => 1.00
        ]);
        ScreeningType::create([
            "name" => "3D",
            "price_multiplier" => 1.30
        ]);
        ScreeningType::create([
            "name" => "4DX",
            "price_multiplier" => 2.00
        ]);
    }
}
