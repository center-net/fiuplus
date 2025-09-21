<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\City;
use App\Models\Village;

class VillageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $villages = [
            // القدس
            ['city' => 'al-quds', 'name' => 'العيزرية', 'slug' => 'al-eizariya'],
            ['city' => 'al-quds', 'name' => 'أبو ديس', 'slug' => 'abu-dis'],
            ['city' => 'al-quds', 'name' => 'السواحرة الشرقية', 'slug' => 'al-sawahra-al-sharqiya'],
            ['city' => 'al-quds', 'name' => 'بيت حنينا', 'slug' => 'beit-hanina'],
            ['city' => 'al-quds', 'name' => 'شعفاط', 'slug' => 'shufat'],
            
            // رام الله والبيرة
            ['city' => 'ramallah-and-al-bireh', 'name' => 'بيتونيا', 'slug' => 'beitunia'],
            ['city' => 'ramallah-and-al-bireh', 'name' => 'دير دبوان', 'slug' => 'deir-dibwan'],
            ['city' => 'ramallah-and-al-bireh', 'name' => 'بيرزيت', 'slug' => 'birzeit'],
            ['city' => 'ramallah-and-al-bireh', 'name' => 'سلواد', 'slug' => 'silwad'],
            ['city' => 'ramallah-and-al-bireh', 'name' => 'عين عريك', 'slug' => 'ein-arik'],

            // الخليل
            ['city' => 'hebron', 'name' => 'حلحول', 'slug' => 'halhul'],
            ['city' => 'hebron', 'name' => 'الظاهرية', 'slug' => 'al-dhahiriya'],
            ['city' => 'hebron', 'name' => 'دورا', 'slug' => 'dura'],
            ['city' => 'hebron', 'name' => 'يطا', 'slug' => 'yatta'],
            ['city' => 'hebron', 'name' => 'بني نعيم', 'slug' => 'bani-naim'],

            // بيت لحم
            ['city' => 'bethlehem', 'name' => 'بيت ساحور', 'slug' => 'beit-sahour'],
            ['city' => 'bethlehem', 'name' => 'بيت جالا', 'slug' => 'beit-jala'],
            ['city' => 'bethlehem', 'name' => 'الخضر', 'slug' => 'al-khader'],
            ['city' => 'bethlehem', 'name' => 'العبيدية', 'slug' => 'al-ubeidiya'],
            ['city' => 'bethlehem', 'name' => 'تقوع', 'slug' => 'tuqu'],

            // نابلس
            ['city' => 'nablus', 'name' => 'حوارة', 'slug' => 'hawara'],
            ['city' => 'nablus', 'name' => 'بيتا', 'slug' => 'beita'],
            ['city' => 'nablus', 'name' => 'سبسطية', 'slug' => 'sebastia'],
            ['city' => 'nablus', 'name' => 'عصيرة الشمالية', 'slug' => 'asira-ash-shamaliya'],
            ['city' => 'nablus', 'name' => 'بلاطة', 'slug' => 'balata'],

            // جنين
            ['city' => 'jenin', 'name' => 'قباطية', 'slug' => 'qabatiya'],
            ['city' => 'jenin', 'name' => 'اليامون', 'slug' => 'al-yamun'],
            ['city' => 'jenin', 'name' => 'سيلة الظهر', 'slug' => 'silat-ad-dhahr'],
            ['city' => 'jenin', 'name' => 'عرابة', 'slug' => 'arraba'],
            ['city' => 'jenin', 'name' => 'برقين', 'slug' => 'burqin'],

            // طولكرم
            ['city' => 'tulkarm', 'name' => 'عتيل', 'slug' => 'attil'],
            ['city' => 'tulkarm', 'name' => 'دير الغصون', 'slug' => 'deir-al-ghusun'],
            ['city' => 'tulkarm', 'name' => 'بلعا', 'slug' => 'balaa'],
            ['city' => 'tulkarm', 'name' => 'عنبتا', 'slug' => 'anabta'],
            ['city' => 'tulkarm', 'name' => 'كفر اللبد', 'slug' => 'kafr-al-labad'],

            // قلقيلية
            ['city' => 'qalqilya', 'name' => 'حبلة', 'slug' => 'habla'],
            ['city' => 'qalqilya', 'name' => 'عزون', 'slug' => 'azzun'],
            ['city' => 'qalqilya', 'name' => 'كفر قدوم', 'slug' => 'kafr-qaddum'],
            ['city' => 'qalqilya', 'name' => 'جيت', 'slug' => 'jit'],
            ['city' => 'qalqilya', 'name' => 'عزون عتمة', 'slug' => 'azzun-atma'],

            // سلفيت
            ['city' => 'salfit', 'name' => 'كفر الديك', 'slug' => 'kafr-ad-dik'],
            ['city' => 'salfit', 'name' => 'بديا', 'slug' => 'bidya'],
            ['city' => 'salfit', 'name' => 'دير بلوط', 'slug' => 'deir-ballut'],
            ['city' => 'salfit', 'name' => 'مردا', 'slug' => 'marda'],
            ['city' => 'salfit', 'name' => 'ياسوف', 'slug' => 'yasuf'],

            // أريحا والأغوار
            ['city' => 'jericho', 'name' => 'العوجا', 'slug' => 'al-auja'],
            ['city' => 'jericho', 'name' => 'فصايل', 'slug' => 'fasayil'],
            ['city' => 'jericho', 'name' => 'الجفتلك', 'slug' => 'al-jiftlik'],
            ['city' => 'jericho', 'name' => 'النويعمة', 'slug' => 'al-nuweima'],
            ['city' => 'jericho', 'name' => 'دير حجلة', 'slug' => 'deir-hajla'],

            // طوباس
            ['city' => 'tubas', 'name' => 'طمون', 'slug' => 'tammun'],
            ['city' => 'tubas', 'name' => 'عقابا', 'slug' => 'aqaba'],
            ['city' => 'tubas', 'name' => 'تياسير', 'slug' => 'tayasir'],
            ['city' => 'tubas', 'name' => 'عين البيضا', 'slug' => 'ein-al-beida'],
            ['city' => 'tubas', 'name' => 'بردلة', 'slug' => 'bardala'],

            // غزة
            ['city' => 'gaza', 'name' => 'الزيتون', 'slug' => 'zeitoun'],
            ['city' => 'gaza', 'name' => 'التفاح', 'slug' => 'tuffah'],
            ['city' => 'gaza', 'name' => 'الشجاعية', 'slug' => 'shujaia'],
            ['city' => 'gaza', 'name' => 'الرمال', 'slug' => 'rimal'],
            ['city' => 'gaza', 'name' => 'الشيخ رضوان', 'slug' => 'sheikh-radwan'],

            // خان يونس
            ['city' => 'khan-yunis', 'name' => 'القرارة', 'slug' => 'al-qarara'],
            ['city' => 'khan-yunis', 'name' => 'الفخاري', 'slug' => 'al-fukhari'],
            ['city' => 'khan-yunis', 'name' => 'المصدر', 'slug' => 'al-musaddar'],
            ['city' => 'khan-yunis', 'name' => 'عبسان', 'slug' => 'abasan'],
            ['city' => 'khan-yunis', 'name' => 'خزاعة', 'slug' => 'khuza-a'],

            // رفح
            ['city' => 'rafah', 'name' => 'الشوكة', 'slug' => 'al-shoka'],
            ['city' => 'rafah', 'name' => 'النصر', 'slug' => 'al-nasr'],
            ['city' => 'rafah', 'name' => 'المواصي', 'slug' => 'al-mawasi'],
            ['city' => 'rafah', 'name' => 'الزهور', 'slug' => 'al-zuhur'],
            ['city' => 'rafah', 'name' => 'تل السلطان', 'slug' => 'tal-al-sultan']
        ];

        foreach ($villages as $village) {
            $city = City::where('slug', $village['city'])->first();
            if ($city) {
                $model = Village::firstOrCreate([
                    'city_id' => $city->id,
                    'slug' => $village['slug']
                ]);
                // حفظ الاسم في جدول الترجمات (عربي + إنجليزي)
                $model->translateOrNew('ar')->name = $village['name'];
                $model->translateOrNew('en')->name = ucfirst(str_replace('-', ' ', $village['slug']));
                $model->save();
            }
        }
    }
}
