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
        $era1 = Era::create([
            'name' => "90s",
            'startYear' => '1990-01-01',
            'endYear' => '2000-01-01',              
            'description' => 'A 90-es évek ikonikus filmjei.',
        ]);
        $era1->poster()->create([
            'text' => 'era1990s_poster.jpg',
            'media_type' => 'poster',
            'path' => 'images/era/era1990s_poster.jpg',            
        ]);

        $era2 = Era::create([
            'name' => "00s",
            'startYear' => '2000-01-01',
            'endYear' => '2015-01-01',              
            'description' => 'A 2000-es évek mozifilmjei széles választékban.',
        ]);
        $era2->poster()->create([
            'text' => 'era2000s_poster.jpg',
            'media_type' => 'poster',
            'path' => 'images/era/era2000s_poster.jpg',
        ]);

        $era3 = Era::create([
            'name' => "nowdays",
            'startYear' => '2015-01-01',
            'endYear' => now(),
            'description' => 'Az aktuális filmipar alkotások.',
        ]);
        $era3->poster()->create([
            'text' => 'eraNowdays_poster.jpg',
            'media_type' => 'poster',
            'path' => 'images/era/eraNowdays_poster.jpg',
        ]);
    }
}
