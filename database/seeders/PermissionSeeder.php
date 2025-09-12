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
        Permission::firstOrCreate([
            'name'      => 'تصفح لوحة الإدارة',
            'key'       => 'browse_admin',
            'table_name' => null,
        ]);

        Permission::firstOrCreate([
            'name'      => 'مدير النظام',
            'key'       => 'administrator',
            'table_name' => null,
        ]);

        Permission::firstOrCreate([
            'name'      => 'مدير',
            'key'       => 'manager',
            'table_name' => null,
        ]);

        Permission::firstOrCreate([
            'name'      => 'محظور',
            'key'       => 'banned',
            'table_name' => null,
        ]);

        Permission::generateFor('permissions');
        Permission::generateFor('roles');
        Permission::generateFor('countries');
        Permission::generateFor('cities');
        Permission::generateFor('users');
        Permission::generateFor('villages');
    }
}
