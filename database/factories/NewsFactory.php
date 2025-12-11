<?php

namespace Database\Factories;

use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\News>
 */
class NewsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->words(5, true);
        return [
            'profile_id' => Profile::query()->inRandomOrder()->first()->id,
            'title' => $title,
            'slug' => str($title)->slug(),
            'category' => fake()->randomElement(['Announcement', 'Review', 'Events', 'Behind the scenes']),
            'excerp' => fake()->text(30),   
            'body' => fake()->text(),
            'read_time_min' => fake()->numberBetween(3, 12),
            'external_link' => null
        ];
    }
}
