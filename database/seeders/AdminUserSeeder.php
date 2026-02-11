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
        $user = User::create([
            'username' => 'epoch_admin',
            'email' => 'admin@epoch.test',
            'password_id' => $password->id,            
            'role' => Role::ADMIN,
            'points' => 0,
            'last_login_at' => null
        ]);

        $user->poster()->create([
            'text' => 'epoch_admin_pfp',
            'media_type' => 'poster',
            'path' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTjoZ4FsLBNb1fSk7nPqBX6MAcJRKL10O1uoAFtegwAxK1m8eWmEzVAoJyR2b2skk-vU0OKcxxEv6qLlFfngsgJH0H9IKO2-84lwfHV3w&s=10'
        ]);
    }
}
