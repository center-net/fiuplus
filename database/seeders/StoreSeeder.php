<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Store;
use App\Models\User;

class StoreSeeder extends Seeder
{
    /**
     * Seed stores for users having the 'merchant' role.
     */
    public function run(): void
    {
        // Users with role key 'merchant' should each have a store.
        $merchantUsers = User::whereHas('roles', fn($q) => $q->where('key', 'merchant'))->get();

        foreach ($merchantUsers as $user) {
            $store = Store::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'slug' => $user->username ?: (string) $user->id,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'is_active' => false, // start inactive
                ]
            );

            foreach (['ar', 'en'] as $loc) {
                $name = $loc === 'ar' ? ("متجر " . ($user->name ?: $user->username)) : ("Store " . ($user->name ?: $user->username));
                $store->translateOrNew($loc)->name = $name;
                $store->translateOrNew($loc)->description = $loc === 'ar'
                    ? 'المتجر الافتراضي للتاجر'
                    : 'Default merchant store';
            }

            $store->save();
        }
    }
}