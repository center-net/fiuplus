<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\City;
use App\Models\Country;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $palestine = Country::where('slug','PS')->first()->id;

        $cities = [
            ['name' => 'القدس', 'slug' => 'al-quds'],
            ['name' => 'رام الله والبيرة', 'slug' => 'ramallah-and-al-bireh'],
            ['name' => 'الخليل', 'slug' => 'hebron'],
            ['name' => 'بيت لحم', 'slug' => 'bethlehem'],
            ['name' => 'نابلس', 'slug' => 'nablus'],
            ['name' => 'جنين', 'slug' => 'jenin'],
            ['name' => 'طولكرم', 'slug' => 'tulkarm'],
            ['name' => 'قلقيلية', 'slug' => 'qalqilya'],
            ['name' => 'سلفيت', 'slug' => 'salfit'],
            ['name' => 'أريحا والأغوار', 'slug' => 'jericho'],
            ['name' => 'طوباس', 'slug' => 'tubas'],
            ['name' => 'الجليل', 'slug' => 'galilee'],
            ['name' => 'المثلث', 'slug' => 'triangle'],
            ['name' => 'النقب', 'slug' => 'negev'],
            ['name' => 'الداخل', 'slug' => 'internal'],
            ['name' => 'غزة', 'slug' => 'gaza'],
            ['name' => 'دير البلح', 'slug' => 'deir-al-balah'],
            ['name' => 'النصيرات', 'slug' => 'nuseirat'],
            ['name' => 'البريج', 'slug' => 'al-bureij'],
            ['name' => 'المغازي', 'slug' => 'al-maghazi'],
            ['name' => 'جباليا', 'slug' => 'jabalia'],
            ['name' => 'بيت لاهيا', 'slug' => 'beit-lahia'],
            ['name' => 'بيت حانون', 'slug' => 'beit-hanoun'],
            ['name' => 'خان يونس', 'slug' => 'khan-yunis'],
            ['name' => 'بني سهيلا', 'slug' => 'bani-suheila'],
            ['name' => 'عبسان الكبيرة', 'slug' => 'abasan-al-kabira'],
            ['name' => 'عبسان الجديدة', 'slug' => 'abasan-al-jadida'],
            ['name' => 'خزاعة', 'slug' => 'khuza-a'],
            ['name' => 'رفح', 'slug' => 'rafah'],
            ['name' => 'تل السلطان', 'slug' => 'tal-al-sultan'],
        ];

        foreach ($cities as $c) {
            $model = City::firstOrCreate(
                [
                    'country_id' => $palestine,
                    'slug' => $c['slug'],
                ],
                [
                    'delivery_cost' => 0.00,
                ]
            );
            // حفظ الاسم في جدول الترجمات (عربي + إنجليزي)
            $model->translateOrNew('ar')->name = $c['name'];
            $model->translateOrNew('en')->name = ucfirst(str_replace('-', ' ', $c['slug']));
            $model->save();
        }
    }
}
