<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('cars')->insert([
    [
        'customer_id' => 1,
        'make' => 'تويوتا',
        'model' => 'كورولا',
        'year' => '2020',
        'vin' => '2020',
        'license_plate' => '5-123456',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'customer_id' => 2,
        'make' => 'هيونداي',
        'model' => 'أكسنت',
        'year' => '2018',
        'vin' => '4242',
        'license_plate' => '2-654321',
        'created_at' => now(),
        'updated_at' => now(),
    ],
]);
    }
}
