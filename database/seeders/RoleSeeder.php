<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['key' => 'administrator', 'name' => ['ar' => 'مدير النظام', 'en' => 'Administrator'], 'color' => 'bg-danger text-white'],
            ['key' => 'admin',         'name' => ['ar' => 'مدير',       'en' => 'Admin'],          'color' => 'bg-warning text-dark'],
            ['key' => 'moderator',     'name' => ['ar' => 'مراقب',      'en' => 'Moderator'],      'color' => 'bg-success text-white'],
            ['key' => 'merchant',      'name' => ['ar' => 'تاجر',       'en' => 'Merchant'],       'color' => 'bg-info text-dark'],
            ['key' => 'user',          'name' => ['ar' => 'مستخدم',     'en' => 'User'],           'color' => 'bg-primary text-dark'],
            ['key' => 'banned',        'name' => ['ar' => 'محظور',      'en' => 'Banned'],         'color' => 'bg-secondary text-white'],
        ];

        foreach ($roles as $r) {
            $role = Role::firstOrCreate(['key' => $r['key']], ['color' => $r['color']]);
            foreach (['ar', 'en'] as $loc) {
                $role->translateOrNew($loc)->name = $r['name'][$loc] ?? $r['name']['ar'];
            }
            $role->save();
        }

        $permissionAdministrator = Permission::where('key','!=','banned')->pluck('id')->toArray();

        $adminRole = Role::where('key','administrator')->first();
        if ($adminRole) {
            $adminRole->permissions()->sync($permissionAdministrator);
        }
    }
}
