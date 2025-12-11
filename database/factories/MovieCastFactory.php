<?php

namespace Database\Factories;

use App\Models\Cast_member;
use App\Models\CastMember;
use App\Models\Movie;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MovieCast>
 */
class MovieCastFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'movie_id' => Movie::query()->inRandomOrder()->first()->id,
            'cast_member_id' => CastMember::query()->inRandomOrder()->first()->id,
        ];
    }
}
