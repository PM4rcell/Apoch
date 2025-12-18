<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();
        $user = $request->user();

        $user->tokens()->where('name', 'main')->delete();

        $token = $user->createToken('main')->plainTextToken;

        $user->profile?->update(['last_login_at' => now()]);

        return [
            'user' => new UserResource($user),
            'token' => $token,
        ];
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response
    {
        $user = $request->user();
        $user->tokens()
            ->where('id', $user->currentAccessToken()->id)
            ->delete();        

        return response()->noContent();
    }
}
