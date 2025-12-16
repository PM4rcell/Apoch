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
use App\Services\MediaService;
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
    public function store(StoreMovieRequest $request, MediaService $mediaService)
    {
        $data = $request->validated();        

        if (isset($data['director'])) {
            $director = Director::firstOrCreate([
                'name' => $data['director'],
            ]);
            $data['director_id'] = $director->id;
        }

        $movie = Movie::create([
            'title' => $data['title'],
            'slug' => str($data['title'])->slug(),
            'description' => $data['description'],
            'release_date' => $data['release_date'],
            'director_id' => $data['director_id'],
            'era_id' => $data['era_id'],
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
        
        $pivotData = [];
        foreach ($data['cast'] as $castMember) {
            $cast = CastMember::firstOrCreate(['name' => $castMember['name']]);
            $pivotData[$cast->id] = ['role' => $castMember['role']];
        }
        $movie->cast()->sync($pivotData);

        
        $mediaService->storePoster($movie, $data['omdb_poster_url'] ?? null, $request->file('poster_file'));

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
        $movie->update($data);
    

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

    public function getSimilarMovies($id)
    {
        $movie = Movie::findOrFail($id);
        $genres = $movie->genres()->pluck('genres.id')->toArray();

        $similarMovies = Movie::whereHas('genres', function ($query) use ($genres) {
            $query->whereIn('genres.id', $genres);
        })
        ->where('id', '!=', $movie->id)
        ->with(['poster', 'era'])
        ->distinct()
        ->take(5)
        ->get();

        return MovieSummaryResource::collection($similarMovies);
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
