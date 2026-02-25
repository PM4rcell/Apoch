<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserFullResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\MediaService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::query()->with('poster')->paginate(15);
        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load('achievements', 'comments','bookings', 'watchlist.movie');
        return new UserFullResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();

        $user->update(collect($data)->except('watchlist')->all());

        if (array_key_exists('watchlist', $data)) {
            foreach ($data['watchlist'] as $movieId) {
                $user->watchlist()->firstOrCreate(['movie_id' => $movieId]);
            }
        }

        return new UserFullResource($user->fresh('watchlist'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->noContent();
    }

    public function me(Request $request){
        $user = $request->user();

        $user->load('achievements', 'watchlist.movie.poster', 'comments', 'poster', 'bookings');

        return new UserFullResource($user);
    }
    public function updateMe(UpdateUserRequest $request, MediaService $mediaService){        
        $user = $request->user();
        $data = $request->validated();
        $clearAvatar = array_key_exists('avatar', $data) && is_null($data['avatar']);

        $user->update(collect($data)->except(['watchlist', 'avatar'])->all());

        if (array_key_exists('watchlist', $data)) {
            foreach ($data['watchlist'] as $movieId) {
                $user->watchlist()->firstOrCreate(['movie_id' => $movieId]);
            }
        }

        if ($clearAvatar) {
            $user->poster()->delete();
        } elseif ($request->hasFile('avatar')) {
            $mediaService->storeUploadedPoster($user, $request->file('avatar'));
        }

        // Refresh user from DB to get updated poster
        $user = $user->fresh(['poster', 'achievements', 'watchlist.movie.poster', 'comments']);
        return new UserFullResource($user);
    }

      /**
     * Update only the user's role.
     */
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,user'
        ]);

        $user->update([
            'role' => $request->role
        ]);

        return new UserResource($user);
    }
}
