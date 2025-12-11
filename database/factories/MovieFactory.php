<?php

namespace Database\Factories;

use App\Models\Director;
use App\Models\Era;
use Illuminate\Database\Eloquent\Factories\Factory;
use Psy\Util\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movie>
 */
class MovieFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(3);
        return [            
            'era_id' => Era::query()->inRandomOrder()->first()->id,
            'director_id' => Director::query()->inRandomOrder()->first()->id,
            'title' => $title,
            'description' =>fake()->text(),
            'vote_avg' => fake()->randomFloat(2,1,5),
            'imdb_id' => fake()->numberBetween(0,500),
            'omdb_category' => fake()->randomElement(['Movie', 'Tv']),
            'age_rating' => fake()-> randomElement(['G', 'PG', 'PG-13', 'R']),
            'release_date' =>fake()->date(),
            'runtime_min' =>fake()->numberBetween(60, 200),
            'is_featured' => fake()->boolean(10),
            'slug' => str($title)->slug(),
            'trailer_link' => fake()->url()
        ];
    }
}
