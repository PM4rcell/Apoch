<?php

namespace Database\Seeders;

use App\Models\Movie_cast;
use App\Models\MovieCast;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MovieCastSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MovieCast::factory(20)->create();
    }
}
