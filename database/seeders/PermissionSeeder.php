<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defs = [
            [
                'key' => 'browse_admin',
                'name' => ['ar' => 'تصفح لوحة الإدارة', 'en' => 'Browse Admin Panel'],
                'table' => null
            ],
            [
                'key' => 'administrator',
                'name' => ['ar' => 'مدير النظام', 'en' => 'Administrator'],
                'table' => null
            ],
            [
                'key' => 'manager',
                'name' => ['ar' => 'مدير', 'en' => 'Manager'],
                'table' => null
            ],
            [
                'key' => 'banned',
                'name' => ['ar' => 'محظور', 'en' => 'Banned'],
                'table' => null
            ],
            [
                'key' => 'manage_roles',
                'name' => ['ar' => 'إدارة الأدوار', 'en' => 'Manage roles'],
                'table' => null
            ],
            [
                'key' => 'addPermission_users',
                'name' => ['ar' => 'إضافة صلاحيات مباشرة للمستخدمين', 'en' => 'Add direct permissions to users'],
                'table' => 'users'
            ],
        ];

        foreach ($defs as $d) {
            $perm = Permission::firstOrCreate(['key' => $d['key']]);
            foreach (['ar', 'en'] as $loc) {
                $perm->translateOrNew($loc)->name = $d['name'][$loc] ?? $d['name']['ar'];
                $perm->translateOrNew($loc)->table_name = $d['table'];
            }
            $perm->save();
        }

        Permission::generateFor('permissions');
        Permission::generateFor('roles');
        Permission::generateFor('countries');
        Permission::generateFor('cities');
        Permission::generateFor('users');
        Permission::generateFor('villages');
        Permission::generateFor('stores');
    }
}
