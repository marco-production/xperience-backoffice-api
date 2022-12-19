<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Marco',
            'lastname' => 'De la cruz',
            'email' => 'super@hotmail.com',
            'country_id' => 69,
            'password' => Hash::make('123456'),
            'is_verified' => true,
            'avatar' => 'default.png',
            'remember_token' => Str::random(10),
        ])->assignRole('Super Admin');

        User::create([
            'name' => 'Marco',
            'lastname' => 'De la cruz',
            'email' => 'admin@hotmail.com',
            'country_id' => 69,
            'password' => Hash::make('123456'),
            'is_verified' => true,
            'avatar' => 'default.png',
            'remember_token' => Str::random(10),
        ])->assignRole('Admin');

        User::create([
            'name' => 'Marco',
            'lastname' => 'Trinidad',
            'email' => 'marco@hotmail.com',
            'country_id' => 50,
            'password' => Hash::make('123456'),
            'is_verified' => true,
            'avatar' => 'default.png',
            'remember_token' => Str::random(10),
        ])->assignRole('User');
    }
}
