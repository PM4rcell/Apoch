<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\Director;
use App\Models\Era;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\Password;
use App\Models\User;
use App\Services\MediaService;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery\MockInterface;
use Tests\TestCase;

class MovieControllerTest extends TestCase
{

    use RefreshDatabase;

    

    /**
     * Index function successfully returns movie list.
     */
    public function test_index_returns_movies(): void
    {
        Era::factory()->create();
        Director::factory()->create();
        Movie::factory(3)->create();

        $response = $this->getJson('/api/movies');

        $response->assertOk();
        $response->assertJsonStructure([
            'data',
            'links',
            'meta'
        ]);
    }

    /**
     * Index function successfully returns movie list filtered by era.
     */
     public function test_index_filters_by_era_id(): void
    {
        $era1 = Era::factory()->create();
        $era2 = Era::factory()->create();

        Director::factory()->create();

        $movie1 = Movie::factory()->create(['era_id' => $era1->id]);
        $movie2 = Movie::factory()->create(['era_id' => $era2->id]);

        $response = $this->getJson('/api/movies?era_id=' . $era1->id);

        $response->assertOk();
        $response->assertJsonFragment(['title' => $movie1->title]);
        $response->assertJsonMissing(['title' => $movie2->title]);
    }

    /**
     * Index function successfully returns movie list filtered by title search.
     */
    public function test_index_filters_by_search_query(): void
    {
        Era::factory()->create();
        Director::factory()->create();
        $match = Movie::factory()->create(['title' => 'The Matrix']);
        $other = Movie::factory()->create(['title' => 'Inception']);

        $response = $this->getJson('/api/movies?q=Matrix');

        $response->assertOk();
        $response->assertJsonFragment(['title' => $match->title]);
        $response->assertJsonMissing(['title' => $other->title]);
    }

     public function test_show_returns_movie(): void
    {
        Era::factory()->create();
        Director::factory()->create();
        $movie = Movie::factory()->create();

        $response = $this->getJson('/api/movies/' . $movie->id);

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                'id',
            ],
        ]);
        $response->assertJsonPath('data.id', $movie->id);
    }

    public function test_store_creates_movie_with_genres_and_cast_and_uploaded_poster(): void
    {
        $pass = Password::factory()->create();
         $admin = User::factory()->create([
             'role' => Role::ADMIN,
             'password_id' => $pass->id
        ]);
        $token = $admin->createToken('test-token')->plainTextToken;

        $era = Era::factory()->create();
        $genre = Genre::factory()->create();
        $director = Director::factory()->create();

        $this->mock(MediaService::class, function (MockInterface $mock) {
            $mock->shouldReceive('storeUploadedPoster')->once();
            $mock->shouldReceive('storeExternalPoster')->never();
            $mock->shouldReceive('storeUploadedMedia')->never();
            $mock->shouldReceive('storeExternalMedia')->never();
        });

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
->postJson('/api/admin/movies', [
            'title' => 'Interstellar',
            'description' => 'A sci-fi film.',
            'release_date' => '2014-11-07',
            'vote_avg' => 5,
            'director_id' => (string) $director->id ,
            'era_id' => $era->id,
            'runtime_min' => 169,
            'genres' => [(string) $genre->id],
            'cast' => [
                [
                    'name' => 'Matthew McConaughey',
                    'role' => 'Cooper',
                ],
            ],
            'poster_file' => UploadedFile::fake()->create('poster.jpg', 100, 'image/jpeg'),
        ]);

        $response->assertSuccessful();
        $response->assertJsonPath('data.title', 'Interstellar');

        $this->assertDatabaseHas('movies', [
            'title' => 'Interstellar',
            'era_id' => $era->id,
            'omdb_category' => 'movie',
        ]);

        $this->assertDatabaseHas('genres', [
            'id' => $genre->id,
        ]);

        $this->assertDatabaseHas('cast_members', [
            'name' => 'Matthew McConaughey',
        ]);
    }

     public function test_store_uses_external_poster_when_external_url_is_present(): void
    {
        $era = Era::factory()->create();
        $genre = Genre::factory()->create();        
        $director = Director::factory()->create();

        $pass = Password::factory()->create();
         $admin = User::factory()->create([
             'role' => Role::ADMIN,
             'password_id' => $pass->id
        ]);
        $token = $admin->createToken('test-token')->plainTextToken;

        $this->mock(MediaService::class, function (MockInterface $mock) {
            $mock->shouldReceive('storeExternalPoster')->once();
            $mock->shouldReceive('storeUploadedPoster')->never();
            $mock->shouldReceive('storeUploadedMedia')->never();
            $mock->shouldReceive('storeExternalMedia')->never();
        });

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->postJson('/api/admin/movies', [
            'title' => 'Alien',
            'description' => 'A horror sci-fi film.',
            'release_date' => '1979-05-25',
            'director_id' => (string) $director->id,
            'vote_avg' => 5,
            'era_id' => $era->id,
            'runtime_min' => 117,
            'genres' => [(string) $genre->id],
            'cast' => [
                [
                    'name' => 'Sigourney Weaver',
                    'role' => 'Ripley',
                ],
            ],
            'external_url' => 'https://example.com/poster.jpg',
        ]);

        $response->assertSuccessful();
    }

     public function test_store_stores_gallery_files_and_urls(): void
    {
        $era = Era::factory()->create();
        $genre = Genre::factory()->create();        
        $director = Director::factory()->create();

        $pass = Password::factory()->create();
         $admin = User::factory()->create([
             'role' => Role::ADMIN,
             'password_id' => $pass->id
        ]);
        $token = $admin->createToken('test-token')->plainTextToken;

        $this->mock(MediaService::class, function (MockInterface $mock) {
            $mock->shouldReceive('storeUploadedPoster')->never();
            $mock->shouldReceive('storeExternalPoster')->never();
            $mock->shouldReceive('storeUploadedMedia')->twice();
            $mock->shouldReceive('storeExternalMedia')->twice();
        });

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->postJson('/api/admin/movies', [
            'title' => 'Blade Runner',
            'description' => 'Neo-noir sci-fi.',
            'release_date' => '1982-06-25',
            'director_id' => (string) $director->id,
            'era_id' => $era->id,
            'runtime_min' => 117,
            'vote_avg' => 4.8,
            'genres' => [(string) $genre->id],
            'cast' => [
                [
                    'name' => 'Harrison Ford',
                    'role' => 'Deckard',
                ],
            ],
            'gallery_files' => [
               UploadedFile::fake()->create('g1.jpg', 100, 'image/jpeg'),
                UploadedFile::fake()->create('g2.jpg', 100, 'image/jpeg')
            ],
            'gallery_urls' => [
                'https://example.com/1.jpg',
                'https://example.com/2.jpg',
            ],
        ]);

        $response->assertSuccessful();
    }

    public function test_update_updates_movie_and_replaces_relations(): void
    {
        $era = Era::factory()->create();
        $genre1 = Genre::factory()->create();
        $genre2 = Genre::factory()->create();

        Director::factory()->create();

        $movie = Movie::factory()->create();

        $pass = Password::factory()->create();
         $admin = User::factory()->create([
             'role' => Role::ADMIN,
             'password_id' => $pass->id
        ]);
        $token = $admin->createToken('test-token')->plainTextToken;

        $this->mock(MediaService::class, function (MockInterface $mock) {
            $mock->shouldReceive('storeUploadedPoster')->once();
            $mock->shouldReceive('storeExternalPoster')->never();
            $mock->shouldReceive('storeUploadedMedia')->once();
            $mock->shouldReceive('storeExternalMedia')->once();
        });

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->putJson('/api/admin/movies/' . $movie->id, [
            'title' => 'Updated Title',
            'description' => 'Updated description.',
            'release_date' => '2020-01-01',
            'vote_avg' => 4.5,
            'director_id' => (string) $movie->director_id,
            'external_url' => $data['external_url'] ?? '',
            'era_id' => $era->id,
            'runtime_min' => 120,
            'genres' => [(string) $genre1->id, (string) $genre2->id],
            'cast' => [
                [
                    'name' => 'Actor One',
                    'role' => 'Lead',
                ],
            ],
            'poster_file' => UploadedFile::fake()->create('poster.jpg', 100, 'image/jpeg'),
            'gallery_files' => [
                UploadedFile::fake()->create('g1.jpg', 100, 'image/jpeg')
            ],
            'gallery_urls' => [
                'https://example.com/gallery.jpg',
            ],
        ]);

        $response->assertOk();
        $response->assertJsonPath('data.title', 'Updated Title');

        $this->assertDatabaseHas('movies', [
            'id' => $movie->id,
            'title' => 'Updated Title',
        ]);
    }

    public function test_destroy_deletes_movie(): void
    {
        Era::factory()->create();
        Director::factory()->create();
        $movie = Movie::factory()->create();

        $pass = Password::factory()->create();
         $admin = User::factory()->create([
             'role' => Role::ADMIN,
             'password_id' => $pass->id
        ]);
        $token = $admin->createToken('test-token')->plainTextToken;

        $response = $this ->withHeader('Authorization', 'Bearer ' . $token)->deleteJson('/api/admin/movies/' . $movie->id);

        $response->assertNoContent();
        $this->assertSoftDeleted($movie);
    }

    public function test_get_similar_movies_returns_movies_with_shared_genres(): void
    {
        $genre = Genre::factory()->create();

        Era::factory()->create();
        Director::factory()->create();

        $movie = Movie::factory()->create();
        $similar = Movie::factory()->create();
        $different = Movie::factory()->create();

        $movie->genres()->sync([$genre->id]);
        $similar->genres()->sync([$genre->id]);

        $response = $this->getJson('/api/movies/' . $movie->id . '/similar');

        $response->assertOk();
        $response->assertJsonFragment(['id' => $similar->id]);
        $response->assertJsonMissing(['id' => $different->id]);
    }
}
