<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class UseCookieTokenForSanctum
{
    /**
     * Authenticate the request directly from the auth cookie.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response|JsonResponse
    {
        $plainTextToken = $request->bearerToken();

        if (! is_string($plainTextToken) || $plainTextToken === '') {
            $plainTextToken = $request->cookie('auth_token');
        }

        if (! is_string($plainTextToken) || $plainTextToken === '') {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $accessToken = PersonalAccessToken::findToken($plainTextToken);

        if (! $accessToken || ! $accessToken->tokenable) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $user = $accessToken->tokenable->withAccessToken($accessToken);

        $request->setUserResolver(static fn () => $user);

        return $next($request);
    }
}