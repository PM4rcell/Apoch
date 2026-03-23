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
        $device = $this->makeDeviceIdentifier();

        $user->tokens()
            ->where('name', 'main')
            ->where('device', $device)
            ->delete();

        $token = $user->createToken('main');        
        $token->accessToken->forceFill(['device' => $device])->save();

        $user->update(['last_login_at' => now()]);

        if(str_contains($device, 'maui')){
            return [
                'user' => new UserResource($user),            
                'token' => $token->plainTextToken,
            ];
        }   
        
        return response([
            'user' => new UserResource($user),
        ])->cookie(
            cookie(
            name: 'auth_token',
            value: $token,
            minutes: 60 * 24 * 30,      
            path: '/',
            domain: null,               
            secure: true,               
            httpOnly: true,
            sameSite: 'lax',            
    )
);
        
    }

    protected function makeDeviceIdentifier(){
        
        $ua = request()->userAgent();
        $app = request()->header('X-App');
        $ip = request()->ip();

        $client = $app === "maui" ? 'maui' : 'web';

        if(str_contains($ua, 'Windows')){
            $platform = 'Win';
        } 
        elseif(str_contains($ua, 'Android')){
            $platform = 'Android';
        } 
        elseif(str_contains($ua, 'iPhone') || str_contains($ua, 'iOS')){
            $platform = 'iOS';
        } 
        elseif(str_contains($ua, 'Mac')){
            $platform = 'MacOS';
        } 
        elseif(str_contains($ua, 'Linux')){
            $platform = 'Linux';
        } 
        else{
            $platform = 'Unknown';
        } 

        if($client === 'maui'){
            $browser = 'MAUI';
        }
        elseif(str_contains($ua, 'Chrome')){
            $browser = 'Chrome';
        }
        elseif(str_contains($ua, 'FireFox')){
            $browser = 'FireFox';
        }
        elseif(str_contains($ua, 'Safari')){
            $browser = 'Safari';
        }
        else{
            $browser = 'Other';
        }

        $hashedIp = substr(hash('sha256', $ip), 0, 8);

        return "$client:$platform:-$browser-$hashedIp";
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
