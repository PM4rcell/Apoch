<?php

namespace App\Http\Controllers;

use App\Models\ProfileWatchlist;
use App\Http\Requests\StoreProfileWatchlistRequest;
use App\Http\Requests\UpdateProfileWatchlistRequest;
use App\Http\Resources\WatchlistResource;
use App\Models\Movie;
use Illuminate\Http\Request;

class ProfileWatchlistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $watchlisted = ProfileWatchlist::query()->with(['user', 'movie'])->paginate(30);
        return WatchlistResource::collection($watchlisted);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProfileWatchlistRequest $request, Movie $movie)
    {
        $user = $request->user();
         
        $exists = $user->watchlist()
            ->where('movie_id', $movie->id)
            ->whereNull('deleted_at') 
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Movie already in your watchlist'
            ], 409);
        }

        $watchlistItem = $user->watchlist()->create([
            'user_id' => $user->id,
            'movie_id' => $movie->id
        ]);

        $watchlistItem->load('user', 'movie');
        return new WatchlistResource($watchlistItem);
    }

    /**
     * Display the specified resource.
     */
    public function show(ProfileWatchlist $profileWatchlist)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProfileWatchlistRequest $request, ProfileWatchlist $profileWatchlist)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, ProfileWatchlist $profileWatchlist)
    {
        if ($profileWatchlist->user_id !== $request->user()?->id) {
        return response()->json(['msg' => 'Forbidden'], 403);
        }

        $profileWatchlist->delete();

        return response()->noContent();
    }
}
