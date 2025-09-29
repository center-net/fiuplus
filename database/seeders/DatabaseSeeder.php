<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * تهيئة قاعدة البيانات بالبيانات الأولية.
     * يجب مراعاة الترتيب بسبب العلاقات بين الجداول
     */
    public function run(): void
    {
        // أولاً: الصلاحيات والأدوار (علاقة العديد للعديد)
        $this->call([
            PermissionSeeder::class, // إنشاء الصلاحيات أولاً
            RoleSeeder::class,       // ثم الأدوار وربطها بالصلاحيات
        ]);

        // ثانياً: التقسيمات الجغرافية (علاقات متسلسلة)
        $this->call([
            CountrySeeder::class,    // الدول أولاً
            CitySeeder::class,       // ثم المدن
            VillageSeeder::class,    // ثم القرى
        ]);

        // ثالثاً: أقسام المتاجر ثم المتاجر للتجار
        $this->call([
            StoreCategorySeeder::class,
            StoreSeeder::class,
        ]);

        // في بيئة التطوير فقط: إنشاء بيانات عشوائية للاختبار
        if (app()->environment('local')) {
            $this->call([
                ProfileSeeder::class,
                UserSettingsSeeder::class,
            ]);

            // إنشاء مستخدم مدير النظام (الاسم عبر الترجمة)
            /** @var \App\Models\User $admin */
            $admin = \App\Models\User::factory()->withRole('administrator')->create([
                'password' => bcrypt('123123'), // كلمة المرور: 123123
                'avatar' => 'images/users/default.png',
                'email_verified_at' => now(),
                'last_seen' => now(),
                'username' => 'admin',
                'email' => 'admin@example.com',
                'phone' => '+970599999999', // رقم هاتف افتراضي للمدير
            ]);
            $admin->translateOrNew('ar')->name = 'مدير النظام';
            $admin->translateOrNew('en')->name = 'Administrator';
            $admin->save();

            // إنشاء مستخدمين عشوائيين (سيتم تعيين دور 'user' لهم تلقائياً عبر الفاكتوري)
            $countryId = optional(\App\Models\Country::where('slug', 'PS')->first())->id;
            $cityId = optional(\App\Models\City::where('slug', 'al-quds')->first())->id;
            
            \App\Models\User::factory(10)->create([
                'country_id' => $countryId,
                'city_id' => $cityId
            ]);
        }
    }
}