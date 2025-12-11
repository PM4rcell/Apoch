<?php

namespace Database\Seeders;

use App\Models\ProfileWatchlist;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfileWatchlistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProfileWatchlist::factory(20)->create();
    }
}
