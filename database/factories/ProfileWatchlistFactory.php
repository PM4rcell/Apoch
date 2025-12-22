<?php

namespace Database\Factories;

use App\Models\Movie;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProfileWatchlist>
 */
class ProfileWatchlistFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::query()->inRandomOrder()->first()->id,
            'movie_id' => Movie::query()->inRandomOrder()->first()->id,            
        ];
    }
}
