<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MechanicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mechanics')->insert([
    [
        'name' => 'سامي الفني',
        'specialty' => 'ميكانيكا عامة',
        'phone' => '0920001122',
        'work_pct' => 15.00,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'عبدالسلام الكهربائي',
        'specialty' => 'كهرباء سيارات',
        'phone' => '0911122334',
        'work_pct' => 20.00,
        'created_at' => now(),
        'updated_at' => now(),
    ],
]);
    }
}
