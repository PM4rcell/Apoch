<?php

namespace Database\Factories;

use App\Models\Achievement;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProfileAchievement>
 */
class ProfileAchievementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'profile_id' => Profile::query()->inRandomOrder()->first()->id,
            'achievement_id' => Achievement::query()->inRandomOrder()->first()->id,
        ];
    }
}
