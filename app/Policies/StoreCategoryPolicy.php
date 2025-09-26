<?php

namespace App\Policies;

use App\Models\StoreCategory;
use App\Models\User;

class StoreCategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('browse_store_categories')
            || $user->hasPermission('browse_stores'); // fallback for admins
    }

    public function view(User $user, StoreCategory $category): bool
    {
        return $user->hasPermission('read_store_categories')
            || $user->hasPermission('read_stores');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('add_store_categories')
            || $user->hasPermission('add_stores');
    }

    public function update(User $user, StoreCategory $category): bool
    {
        return $user->hasPermission('edit_store_categories')
            || $user->hasPermission('edit_stores');
    }

    public function delete(User $user, StoreCategory $category): bool
    {
        return $user->hasPermission('delete_store_categories')
            || $user->hasPermission('delete_stores');
    }

    public function restore(User $user, StoreCategory $category): bool
    {
        return $user->hasPermission('restore_store_categories')
            || $user->hasPermission('restore_stores');
    }

    public function forceDelete(User $user, StoreCategory $category): bool
    {
        return $user->hasPermission('forceDelete_store_categories')
            || $user->hasPermission('forceDelete_stores');
    }
}