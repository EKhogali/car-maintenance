<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CustomerSeeder::class,
            MechanicSeeder::class,
            CarSeeder::class,
            PartSeeder::class,
            ServiceTypeSeeder::class,
            MaintenanceRecordSeeder::class,
        ]);
    }
}
