<?php

namespace App\Http\Controllers;

use App\Http\Resources\MovieDetailResource;
use App\Models\Movie;
use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Http\Resources\MovieSummaryResource;
use App\Models\CastMember;
use App\Models\Director;
use App\Models\Era;
use App\Models\Genre;
use App\Models\Media;
use App\Models\MovieCast;
use App\Models\MovieGenre;
use Psy\Util\Str;
use Symfony\Component\HttpFoundation\Request;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $movies = Movie::query()->with('poster');

        if (request()->filled('era_id')) {
            $movies->where('era_id', request()->input('era_id'));
        }
        if (request()->filled('q')) {
            $q = request()->input('q');
            $movies->where('title', 'LIKE', "%$q%");
        }
        if (request()->boolean('in_cinema')) {
            $movies->wherehas('screenings', function ($q) {
                $q->whereDate('time', '>=', now());
            });
        }

        $movies->orderBy('release_date', 'desc');
        return MovieSummaryResource::collection($movies->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMovieRequest $request)
    {
        $data = $request->validated();
        $era_id = Era::where('name', $data['era'])->first()->id;

        $director = Director::firstOrCreate([
            'name' => $data['director'],
        ]);

        $movie = Movie::create([
            'title' => $data['title'],
            'slug' => str($data['title'])->slug(),
            'description' => $data['description'],
            'release_date' => $data['release_date'],
            'director_id' => $director->id,
            'era_id' => $era_id,
            'vote_avg' => $data['vote_avg'] ?? 1,
            'imdb_id' => $data['imdb_id'] ?? 0,
            'age_rating' => $data['age_rating'] ?? "NR",
            'runtime_min' => $data['runtime_min'],
            'trailer_link' => $data['trailer_link'] ?? '',
            'omdb_category' => 'movie',
        ]);

        $genreIds = [];
        foreach ($data['genres'] as $genre) {
            $genre = Genre::firstOrCreate(['name' => $genre]);
            $genreIds[] = $genre->id;
        }
        $movie->genres()->sync($genreIds);


        $castData = $data['cast'];
        $pivotData = [];
        foreach ($data['cast'] as $castMember) {
            $cast = CastMember::firstOrCreate(['name' => $castMember['name']]);
            $pivotData[$cast->id] = ['role' => $castMember['role']];
        }
        $movie->cast()->sync($pivotData);

        $this->storeMoviePoster($movie, $data['omdb_poster_url'] ?? null, $request->file('poster_file'));
        if ($request->hasFile('gallery')) {
            $this->storeMovieGallery($movie, $request->file('gallery'));
        }

        $movie->load(['poster', 'gallery', 'director', 'era', 'cast', 'genres']);
        return new MovieDetailResource($movie);
    }

    /**
     * Display the specified resource.
     */
    public function show(Movie $movie)
    {
        $movie->load(['poster', 'gallery', 'director', 'era', 'cast', 'genres']);
        return new MovieDetailResource($movie);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMovieRequest $request, Movie $movie)
    {        
        $data = $request->validated();        
        if (isset($data['director'])) {
            $director = Director::firstOrCreate([
                'name' => $data['director'],
            ]);
            $data['director_id'] = $director->id;
        }
        if (isset($data['era'])) {
            $era = Era::firstOrCreate([
                'name' => $data['era'],
            ]);
            $data['era_id'] = $era->id;
        }        
        $movie->update([
        'title'        => $data['title']        ?? $movie->title,
        'description'  => $data['description']  ?? $movie->description,
        'release_date' => $data['release_date'] ?? $movie->release_date,
        'era_id'       => $data['era_id']       ?? $movie->era_id,
        'director_id'  => $data['director_id']  ?? $movie->director_id,
        'vote_avg'     => $data['vote_avg']     ?? $movie->vote_avg,
        'imdb_id'      => $data['imdb_id']      ?? $movie->imdb_id,
        'age_rating'   => $data['age_rating']   ?? $movie->age_rating,
        'runtime_min'  => $data['runtime_min']  ?? $movie->runtime_min,
        'trailer_link' => $data['trailer_link'] ?? $movie->trailer_link,
        'omdb_category'=> $data['omdb_category']?? $movie->omdb_category,
    ]);

    if (array_key_exists('genres', $data)) {
        $genreIds = [];

        foreach ($data['genres'] as $genreName) {
            $genre = Genre::firstOrCreate(['name' => $genreName]);
            $genreIds[] = $genre->id;
        }
        
        $movie->genres()->sync($genreIds);
    }
    
    if (array_key_exists('cast', $data)) {
        $pivotData = []; 

        foreach ($data['cast'] as $castMember) {
            $cast = CastMember::firstOrCreate(['name' => $castMember['name']]);
            $pivotData[$cast->id] = ['role' => $castMember['role']];
        }

        $movie->cast()->sync($pivotData);
    }
        

        return new MovieDetailResource($movie->fresh()->load(['poster', 'gallery', 'director', 'era', 'cast', 'genres']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movie $movie)
    {
        $movie->delete();
        return response()->noContent();
    }

    private function storeMoviePoster($movie, ?string $omdbPosterUrl, $uploadedFile)
    {
        $movie->poster()->delete();

        if ($uploadedFile) {

            $originalName = $uploadedFile->getClientOriginalName();
            $sanitizedName = preg_replace('/[^a-zA-Z0-9-_\.]/', '_',
            pathinfo($originalName, PATHINFO_FILENAME));
            $extension = $uploadedFile->getClientOriginalExtension();

            $safeName = $sanitizedName . '_' . uniqid() . '.' . $extension;
            $path = $uploadedFile->storeAs('images/movies/'. $movie->slug, $safeName, 'public');
            Media::create([
                'text' => $movie->slug . ' Poster' . $extension,
                'connected_table' => 'movies',
                'connected_id' => $movie->id,
                'media_type' => 'poster',
                'path' => $path,
            ]);
        }

        if ($omdbPosterUrl) {
            Media::create([
                'text' => $movie->slug . ' Poster' . '.jpg',
                'connected_table' => 'movies',
                'connected_id' => $movie->id,
                'media_type' => 'poster',
                'path' => $omdbPosterUrl,
            ]);
        }
    }

    private function storeMovieGallery($movie, $uploadedFiles = [])
    {
        $extension = ".jpg";
        foreach ($uploadedFiles as $file) {
            $path = $file->store('images/movies/'. $movie->slug, 'public');
            Media::create([
                'text' => $movie->slug . ' Image' . uniqid() . $extension,
                'connected_table' => 'movies',
                'connected_id' => $movie->id,
                'media_type' => 'image',
                'path' => $path,
            ]);
        }
    }
}
