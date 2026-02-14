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
            "priceMultiplier" => 1.00
        ]);
        ScreeningType::create([
            "name" => "3D",
            "priceMultiplier" => 1.30
        ]);
        ScreeningType::create([
            "name" => "4DX",
            "priceMultiplier" => 2.00
        ]);
    }
}
