<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{

    public function run()
    {
        User::create([
            'login' => 'leha_cool',
            'password' => Hash::make('123456789'),
            'name' => 'Leha',
            'email' => 'aleks1style@gmail.com',
            'avatar' => null,
            'rating' => 5,
            'role' => 'admin',
            'remember_token' => null
        ]);

        User::create([
            'login' => 'zeka_loh',
            'password' => Hash::make('123456789'),
            'name' => 'Zeka',
            'email' => 'zeka@gamil.com',
            'avatar' => null,
            'rating' => 0,
            'role' => 'user',
            'remember_token' => null
        ]);

        User::create([
            'login' => 'vlad_mosch',
            'password' => Hash::make('123456789'),
            'name' => 'Vlad',
            'email' => 'vlad@gamil.com',
            'avatar' => null,
            'rating' => 5,
            'role' => 'user',
            'remember_token' => null
        ]);
    }
}
