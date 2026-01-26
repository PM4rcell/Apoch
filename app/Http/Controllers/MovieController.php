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
use GuzzleHttp\Psr7\UploadedFile;
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
        return MovieSummaryResource::collection($movies->paginate(6));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMovieRequest $request, MediaService $mediaService)
    {
        //validate
        $data = $request->validated();        

        //find or create director
        if (isset($data['director'])) {
            $director = Director::firstOrCreate([
                'name' => $data['director'],
            ]);
            $data['director_id'] = $director->id;
        }

        //create movie
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

        //find or create genres, cast
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

        //store poster    
        if (!empty($data['external_url'])) {
            $mediaService->storeExternalPoster($movie, $data['external_url']);
        } elseif ($request->hasFile('poster_file')) {
            $mediaService->storeUploadedPoster($movie, $request->file('poster_file'));
        }        

        //store images
        $gallery = $request->input('gallery', []);
        $galleryFiles = $request->file('gallery', []);

        foreach ($gallery as $index => $item) {
            if(isset($galleryFiles[$index]) && $galleryFiles[$index] instanceof UploadedFile){
                $mediaService->storeUploadedMedia($movie,$galleryFiles[$index]);
                continue;
            }

            if(is_string($item) && filter_var($item, FILTER_VALIDATE_URL)){
                $mediaService->storeExternalMedia($movie, $item);
                continue;
            }
        }

        //return movie
        $movie->load(['poster', 'gallery', 'director', 'era', 'cast', 'genres']);
        return new MovieDetailResource($movie);
    }

    /**
     * Display the specified resource.
     */
    public function show(Movie $movie)
    {
        $movie->load(['poster', 'gallery', 'director', 'era', 'cast', 'genres', 'comments.user.poster']);
        return new MovieDetailResource($movie);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMovieRequest $request, Movie $movie, MediaService $mediaService)
    {                
        $data = $request->validated();         
            
        $director = Director::firstOrCreate(['name' => $data['director'],]);
        $data['director_id'] = $director->id;
        
        $movie->update($data);
    

        //Update Genre Relations
        $genreIds = [];
        foreach ($data['genres'] as $genreName) {
            $genre = Genre::firstOrCreate(['name' => $genreName]);
            $genreIds[] = $genre->id;        
        }        
        $movie->genres()->sync($genreIds);
    
        //Update Casting Data
        $pivotData = []; 
        foreach ($data['cast'] as $castMember) {
            $cast = CastMember::firstOrCreate(['name' => $castMember['name']]);
            $pivotData[$cast->id] = ['role' => $castMember['role']];
        }

        $movie->cast()->sync($pivotData);
        
     //store poster    
        if (filled($data['external_url'])) {
            $mediaService->storeExternalPoster($movie, $data['external_url']);
        } elseif ($request->hasFile('poster_file')) {
            $mediaService->storeUploadedPoster($movie, $request->file('poster_file'));
        }

     //store images
        $gallery = $request->input('gallery', []);
        $galleryFiles = $request->file('gallery', []);

        foreach ($gallery as $index => $item) {
            if(isset($galleryFiles[$index]) && $galleryFiles[$index] instanceof UploadedFile){
                $mediaService->storeUploadedMedia($movie,$galleryFiles[$index]);
                continue;
            }

            if(is_string($item) && filter_var($item, FILTER_VALIDATE_URL)){
                $mediaService->storeExternalMedia($movie, $item);
                continue;
            }
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
}
