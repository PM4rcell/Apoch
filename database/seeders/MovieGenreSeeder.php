<?php

namespace Database\Seeders;

use App\Models\Movie_genre;
use App\Models\MovieGenre;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MovieGenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MovieGenre::factory(10)->create();
    }
}
