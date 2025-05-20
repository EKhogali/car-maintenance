<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
        [
            'name' => 'elmo',
            'email' => 'elmo@gmail.com',
            'password' => Hash::make('123'),
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'name' => 'Sherif Nakokh',
            'email' => 'Shr.roro@gmail.com',
            'password' => Hash::make('sh2025'),
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'name' => 'Mohamed Abdulmalik',
            'email' => 'm.abdelmalik21@gmail.com',
            'password' => Hash::make('m2025'),
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'name' => 'Hassan algasier',
            'email' => 'hassan@gmail.com',
            'password' => Hash::make('h2025'),
            'created_at' => now(),
            'updated_at' => now(),
        ],
    ]);
    }
}
