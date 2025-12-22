<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Password;
use App\Models\User;
use App\Role;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): Response
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $password = Password::create([
            'password_hash' => Hash::make($request->password),
            'password_token' => '',
            'old1' => null,
            'old2' => null,
            'old3' => null,
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password_id' => $password->id,
            'role' => 'user',
            'points' => 0,
            'last_login_at' => null

        ]);

        event(new Registered($user));        

        return response()->noContent();
    }
}
