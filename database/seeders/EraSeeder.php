<?php

namespace Database\Seeders;

use App\Models\Era;
use Date;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Era::create([
            'name' => "90s",
            'startYear' => '1990-01-01',
            'endYear' => '2000-01-01',              
            'description' => 'A 90-es évek ikonikus filmjei.',
        ]);
        Era::create([
            'name' => "00s",
            'startYear' => '2000-01-01',
            'endYear' => '2015-01-01',              
            'description' => 'A 2000-es évek mozifilmjei széles választékban.',
        ]);
        Era::create([
            'name' => "nowdays",
            'startYear' => '2015-01-01',
            'endYear' => now(),
            'description' => 'Az aktuális filmipar alkotások.',
        ]);
    }
}
