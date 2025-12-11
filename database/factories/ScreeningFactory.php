<?php

namespace Database\Factories;

use App\Models\Auditorium;
use App\Models\Language;
use App\Models\Movie;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Screening>
 */
class ScreeningFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'auditorium_id' => Auditorium::query()->inRandomOrder()->first()->id,
            'movie_id' => Movie::query()->inRandomOrder()->first()->id,
            'language_id' => Language::query()->inRandomOrder()->first()->id,
            'start_time' => fake()->dateTime(),
        ];
    }
}
