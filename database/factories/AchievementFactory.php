<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Achievement>
 */
class AchievementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'=>fake()->words(5, true),
            'description'=>fake()->text(),
            'type'=>fake()->randomElement(['Single', 'Collection']),
            'points'=>fake()->numberBetween(100, 1000),
            'year'=>fake()->year(),
        ];
    }
}
