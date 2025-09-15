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
         
        Role::firstOrCreate([
            'name' => 'مدير النظام',
            'key' => 'administrator',
            'color' => 'bg-danger text-white',
        ]);

        Role::firstOrCreate([
            'name' => 'مدير',
            'key' => 'admin',
            'color' => 'bg-warning text-dark',
        ]);

        Role::firstOrCreate([
            'name' => 'مراقب',
            'key' => 'moderator',
            'color' => 'bg-success text-white',
        ]);

        Role::firstOrCreate([
            'name' => 'مستخدم',
            'key' => 'user',
            'color' => 'bg-primary text-dark',
        ]);

        Role::firstOrCreate([
            'name' => 'محظور',
            'key' => 'banned',
            'color' => 'bg-secondary text-white',
        ]);

        $permission_administrator = Permission::where('key','!=','banned')->pluck('id')->toArray();

        $admin_role = Role::where('key','administrator')->first();
        $admin_role->permission()->sync($permission_administrator);
    }
}
