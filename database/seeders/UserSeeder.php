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
            'password' => Hash::make('123'),
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'name' => 'Mechanic User',
            'email' => 'mechanic@example.com',
            'password' => Hash::make('secret123'),
            'created_at' => now(),
            'updated_at' => now(),
        ],
    ]);
    }
}
