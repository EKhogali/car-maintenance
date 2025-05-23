<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('parts')->insert([
    [
        'name' => 'فلتر زيت',
        'code' => 'OIL-F123',
        'price' => 25.00,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'بطارية',
        'code' => 'BAT-A456',
        'price' => 280.00,
        'created_at' => now(),
        'updated_at' => now(),
    ],
]);
    }
}
