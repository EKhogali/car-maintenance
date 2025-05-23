<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('service_types')->insert([
    ['name' => 'تغيير زيت المحرك',              'price' => 50.00, 'created_at' => now(), 'updated_at' => now()],
    ['name' => 'تغيير فلتر الزيت',              'price' => 30.00, 'created_at' => now(), 'updated_at' => now()],
    ['name' => 'تغيير فلتر الهواء',             'price' => 25.00, 'created_at' => now(), 'updated_at' => now()],
    ['name' => 'تغيير فلتر البنزين',            'price' => 40.00, 'created_at' => now(), 'updated_at' => now()],
    ['name' => 'تغيير شمعات الإشعال (البواجي)', 'price' => 60.00, 'created_at' => now(), 'updated_at' => now()],
    ['name' => 'فحص شامل للمركبة',              'price' => 150.00, 'created_at' => now(), 'updated_at' => now()],
    ['name' => 'صيانة نظام الفرامل',             'price' => 120.00, 'created_at' => now(), 'updated_at' => now()],
    ['name' => 'تغيير فحمات الفرامل',            'price' => 80.00, 'created_at' => now(), 'updated_at' => now()],
    ['name' => 'فحص نظام التعليق',              'price' => 70.00, 'created_at' => now(), 'updated_at' => now()],
    ['name' => 'ضبط ميزانية الإطارات',           'price' => 45.00, 'created_at' => now(), 'updated_at' => now()],
    ['name' => 'تغيير بطارية السيارة',           'price' => 30.00, 'created_at' => now(), 'updated_at' => now()],
    ['name' => 'تنظيف البخاخات',                'price' => 100.00, 'created_at' => now(), 'updated_at' => now()],
    ['name' => 'تغيير زيت ناقل الحركة',          'price' => 90.00, 'created_at' => now(), 'updated_at' => now()],
    ['name' => 'فحص كمبيوتر شامل',              'price' => 70.00, 'created_at' => now(), 'updated_at' => now()],
    ['name' => 'فحص النظام الكهربائي',           'price' => 60.00, 'created_at' => now(), 'updated_at' => now()],
    ['name' => 'تغيير سير التيمن (الكاتينة)',     'price' => 180.00, 'created_at' => now(), 'updated_at' => now()],
    ['name' => 'تغيير سير المكيف',              'price' => 50.00, 'created_at' => now(), 'updated_at' => now()],
    ['name' => 'تغيير زيت المكيف',              'price' => 55.00, 'created_at' => now(), 'updated_at' => now()],
    ['name' => 'تغيير فلتر المكيف',             'price' => 25.00, 'created_at' => now(), 'updated_at' => now()],
    ['name' => 'تنظيف الرديتر وتغيير الماء',     'price' => 65.00, 'created_at' => now(), 'updated_at' => now()],
    ['name' => 'فحص الإطارات والهواء',           'price' => 20.00, 'created_at' => now(), 'updated_at' => now()],
    ['name' => 'فحص لمبة المحرك (Check Engine)', 'price' => 35.00, 'created_at' => now(), 'updated_at' => now()],
    ['name' => 'تشحيم المفاصل',                 'price' => 25.00, 'created_at' => now(), 'updated_at' => now()],
    ['name' => 'تنظيف التكييف الداخلي',          'price' => 45.00, 'created_at' => now(), 'updated_at' => now()],
    ['name' => 'فحص نظام الوقود',               'price' => 60.00, 'created_at' => now(), 'updated_at' => now()],
    ['name' => 'تبديل المصابيح الأمامية',        'price' => 20.00, 'created_at' => now(), 'updated_at' => now()],
]);

    }
}
