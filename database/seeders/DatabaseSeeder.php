<?php

namespace Database\Seeders;

use App\Models\Media;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            MediaSeeder::class,
            EraSeeder::class,
            PasswordSeeder::class,
            UserSeeder::class,
            CinemaSeeder::class,
            AuditoriumSeeder::class,
            DirectorSeeder::class,
            GenreSeeder::class,
            CastMemberSeeder::class,
            MovieSeeder::class,
            MovieCastSeeder::class,
            MovieGenreSeeder::class,
            LanguageSeeder::class,
            ScreeningTypeSeeder::class,
            ScreeningSeeder::class,
            BookingSeeder::class,            
            BookingSeatSeeder::class,
            PaymentSeeder::class,
            TicketTypeSeeder::class,
            BookingTicketSeeder::class,
            ProductTypeSeeder::class,
            BookingProductSeeder::class,
            AchievementSeeder::class,
            CommentSeeder::class,
            NewsSeeder::class,
            ProfileAchievementSeeder::class,
            ProfileWatchlistSeeder::class,
            AdminUserSeeder::class
        ]);
    }
}
