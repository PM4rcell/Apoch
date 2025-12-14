<?php

namespace Database\Seeders;

use App\Models\Media;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        Media::create([
            'text' => 'era1990s_poster.jpg',
            'media_type' => 'poster',
            'path' => 'images/era/era1990s_poster.jpg',
            'connected_id' => 1,
            'connected_table' => 'eras',
        ]);
        Media::create([
            'text' => 'era2000s_poster.jpg',
            'media_type' => 'poster',
            'path' => 'images/era/era2000s_poster.jpg',
            'connected_id' => 2,
            'connected_table' => 'eras',
        ]);
        Media::create([
            'text' => 'eraNowdays_poster.jpg',
            'media_type' => 'poster',
            'path' => 'images/era/eraNowdays_poster.jpg',
            'connected_id' => 3,
            'connected_table' => 'eras',
        ]);
    }
}
