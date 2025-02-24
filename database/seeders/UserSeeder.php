<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // Ganti App\Models\User dengan path model User Anda
use Illuminate\Support\Facades\Hash; // Untuk hash password
use Faker\Factory as Faker; // Untuk data dummy (opsional)

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Contoh 1: Membuat satu user statis (admin)
        User::create([
            'id' => '15-2023-090',
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), // Hash password!
        ]);
    }
}
