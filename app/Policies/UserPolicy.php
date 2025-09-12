<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('browse_users');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        return $user->hasPermission('read_users');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('add_users');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        return $user->hasPermission('edit_users');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->hasPermission('delete_users');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return $user->hasPermission('restore_users');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $user->hasPermission('forceDelete_users');
    }

    /**
     * Determine whether the user can update their own profile.
     */
    public function updateProfile(User $user, User $model): bool
    {
        return $user->id === $model->id || $user->hasPermission('edit_users');
    }

    /**
     * Determine whether the user can change another user's password.
     */
    public function changePassword(User $user, User $model): bool
    {
        return $user->id === $model->id || $user->hasPermission('edit_users');
    }

    /**
     * Determine whether the user can change user roles.
     */
    public function manageRoles(User $user): bool
    {
        return $user->hasPermission('manage_roles');
    }
}
