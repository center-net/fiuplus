<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ابحث/أنشئ الدولة بالاعتماد على الحقول الأساسية فقط
        $country = Country::firstOrCreate(
            ['slug' => 'PS'],
            ['iso3' => 'PSE']
        );

        // احفظ الاسم في جدول الترجمات (عربي + إنجليزي)
        $country->translateOrNew('ar')->name = 'دولة فلسطين';
        $country->translateOrNew('en')->name = 'State of Palestine';
        $country->save();
    }
}
