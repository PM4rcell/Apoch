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

        $remember = $request->boolean('remember', false); 
        $tokenName = $remember ? 'main-remember' : 'main';

        $existingToken = $user->tokens()
            ->where('name', $tokenName)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })  
            ->first()->plainTextToken;

        if ($existingToken) {        
            return [
                'user' => new UserResource($user),
                'token' => $existingToken,
            ];
        }
    
        $user->tokens()->where('name', 'main')->delete();
        $user->tokens()->where('name', $remember ? 'main-remember' : 'main')->delete();        

        $token = $user->createToken($tokenName, [], now()->addDays($remember ? 60 : 1))->plainTextToken;

        $user->update(['last_login_at' => now()]);

        return [
            'user' => new UserResource($user),
            'token' => $token,
            'expires_at'  => now()->addDays($remember ? 365 : 30)
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
