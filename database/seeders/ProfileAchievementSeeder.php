<?php

namespace Database\Seeders;

use App\Models\Profile_achievement;
use App\Models\ProfileAchievement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfileAchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProfileAchievement::factory(10)->create();
    }
}
