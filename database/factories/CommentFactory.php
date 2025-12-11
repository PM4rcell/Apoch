<?php

namespace Database\Factories;

use App\Models\Movie;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
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
            'movie_id' => Movie::query()->inRandomOrder()->first()->id,
            'text' => fake()->text(50),            
            'rating' => fake()->randomFloat(1, 5),
        ];
    }
}
