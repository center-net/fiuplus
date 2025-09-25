<?php

namespace App\Listeners;

use App\Models\Store;
use App\Models\User;

class CreateStoreForMerchant
{
    /**
     * Handle the event.
     */
    public function handle(User $user): void
    {
        // If the user has merchant role and doesn't have a store, create one
        if ($user->hasRole('merchant') && !$user->store) {
            $store = new Store([
                'slug' => $user->username ?: (string) $user->id,
                'email' => $user->email,
                'phone' => $user->phone,
            ]);
            $store->user()->associate($user);

            foreach (['ar', 'en'] as $loc) {
                $name = $loc === 'ar' ? ("متجر " . ($user->name ?: $user->username)) : ("Store " . ($user->name ?: $user->username));
                $store->translateOrNew($loc)->name = $name;
            }

            $store->save();
        }
    }
}