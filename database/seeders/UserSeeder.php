<?php

namespace Database\Seeders;

use App\Models\Password;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(10)->create();

        $password = Password::create([
            'password_hash' => Hash::make('Pass123'),
            'password_token' => ''
        ]);  
        User::create([
            'username' => 'Test_user',
            'email' => 'user@epoch.test',
            'password_id' => $password->id,            
            'role' => Role::USER,
            'points' => 0,
            'last_login_at' => null
        ]);        
    }
}
