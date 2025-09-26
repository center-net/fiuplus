<?php

namespace App\Policies;

use App\Models\Store;
use App\Models\User;

class StorePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('browse_stores');
    }

    public function view(User $user, Store $store): bool
    {
        return $user->hasPermission('read_stores');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('add_stores');
    }

    public function update(User $user, Store $store): bool
    {
        return $user->hasPermission('edit_stores') || $user->id === $store->user_id; // owner can edit
    }

    public function delete(User $user, Store $store): bool
    {
        return $user->hasPermission('delete_stores');
    }

    public function restore(User $user, Store $store): bool
    {
        return $user->hasPermission('restore_stores');
    }

    public function forceDelete(User $user, Store $store): bool
    {
        return $user->hasPermission('forceDelete_stores');
    }

    public function setup(User $user, Store $store): bool
    {
        // Allow admins/managers with edit permission or the merchant owner of the store
        return $user->hasPermission('edit_stores') || ($user->roles->contains('key', 'merchant') && $user->id === $store->user_id);
    }
}