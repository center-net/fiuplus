<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StoreCategory;
use Illuminate\Support\Str;

class StoreCategorySeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'slug' => 'electronics',
                'ar' => ['name' => 'إلكترونيات', 'description' => 'منتجات الإلكترونيات والأجهزة'],
                'en' => ['name' => 'Electronics', 'description' => 'Electronics and devices'],
            ],
            [
                'slug' => 'fashion',
                'ar' => ['name' => 'أزياء', 'description' => 'ملابس وأحذية واكسسوارات'],
                'en' => ['name' => 'Fashion', 'description' => 'Clothing, shoes and accessories'],
            ],
            [
                'slug' => 'supermarket',
                'ar' => ['name' => 'بقالة', 'description' => 'مواد تموينية ومنزلية'],
                'en' => ['name' => 'Supermarket', 'description' => 'Groceries and household'],
            ],
            [
                'slug' => 'home-furniture',
                'ar' => ['name' => 'المنزل والأثاث', 'description' => 'أثاث المنزل والديكور'],
                'en' => ['name' => 'Home & Furniture', 'description' => 'Furniture and home decor'],
            ],
            [
                'slug' => 'beauty-personal-care',
                'ar' => ['name' => 'الجمال والعناية الشخصية', 'description' => 'منتجات التجميل والعناية الشخصية'],
                'en' => ['name' => 'Beauty & Personal Care', 'description' => 'Beauty and personal care products'],
            ],
            [
                'slug' => 'sports-outdoors',
                'ar' => ['name' => 'الرياضة والهواء الطلق', 'description' => 'مستلزمات الرياضة والرحلات الخارجية'],
                'en' => ['name' => 'Sports & Outdoors', 'description' => 'Sports gear and outdoor equipment'],
            ],
            [
                'slug' => 'toys-baby',
                'ar' => ['name' => 'ألعاب وأطفال', 'description' => 'ألعاب الأطفال ومستحضرات الطفل'],
                'en' => ['name' => 'Toys & Baby', 'description' => 'Toys for kids and baby essentials'],
            ],
            [
                'slug' => 'books-stationery',
                'ar' => ['name' => 'كتب وقرطاسية', 'description' => 'كتب وأدوات مكتبية وقرطاسية'],
                'en' => ['name' => 'Books & Stationery', 'description' => 'Books and stationery supplies'],
            ],
            [
                'slug' => 'health-pharmacy',
                'ar' => ['name' => 'الصحة والصيدلية', 'description' => 'منتجات الصحة والأدوية ومستلزمات الصيدلية'],
                'en' => ['name' => 'Health & Pharmacy', 'description' => 'Health products and pharmacy supplies'],
            ],
            [
                'slug' => 'automotive-accessories',
                'ar' => ['name' => 'السيارات والإكسسوارات', 'description' => 'قطع السيارات وإكسسواراتها'],
                'en' => ['name' => 'Automotive & Accessories', 'description' => 'Car parts and accessories'],
            ],
            [
                'slug' => 'pet-supplies',
                'ar' => ['name' => 'مستلزمات الحيوانات الأليفة', 'description' => 'طعام وإكسسوارات الحيوانات الأليفة'],
                'en' => ['name' => 'Pet Supplies', 'description' => 'Pet food and accessories'],
            ],
            [
                'slug' => 'jewelry-watches',
                'ar' => ['name' => 'المجوهرات والساعات', 'description' => 'مجوهرات وساعات واكسسوارات فاخرة'],
                'en' => ['name' => 'Jewelry & Watches', 'description' => 'Jewelry and watches'],
            ],
            [
                'slug' => 'home-appliances',
                'ar' => ['name' => 'الأجهزة المنزلية', 'description' => 'أجهزة منزلية كهربائية'],
                'en' => ['name' => 'Home Appliances', 'description' => 'Home electrical appliances'],
            ],
        ];

        foreach ($items as $i) {
            $cat = StoreCategory::firstOrCreate(['slug' => $i['slug']], []);
            foreach (['ar','en'] as $loc) {
                $cat->translateOrNew($loc)->name = $i[$loc]['name'];
                $cat->translateOrNew($loc)->description = $i[$loc]['description'];
            }
            $cat->save();
        }
    }
}