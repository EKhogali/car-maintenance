<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('customers')->insert([
            [
                'name' => 'عبدالله الصالح',
                'phone' => '0921234567',
                'email' => 'abdullah@example.com',
                'address' => 'طرابلس - شارع الجمهورية',
                'city' => 'طرابلس',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'منى أحمد',
                'phone' => '0917654321',
                'email' => 'mona@example.com',
                'address' => 'بنغازي - حي دبي',
                'city' => 'بنغازي',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
