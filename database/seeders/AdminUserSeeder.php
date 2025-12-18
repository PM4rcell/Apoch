<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\Password;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = Password::create([
            'password_hash' => Hash::make('Admin123'),
            'password_token' => ''
        ]);
        $profile = Profile::create([
            'role' => Role::ADMIN,
            'points' => 0,
            'last_login_at' => null
        ]);
        User::create([
            'username' => 'epoch_admin',
            'email' => 'admin@epoch.test',
            'password_id' => $password->id,
            'profile_id' => $profile->id
        ]);
    }
}
