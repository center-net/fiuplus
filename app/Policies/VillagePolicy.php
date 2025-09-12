<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Village;
use Illuminate\Auth\Access\Response;

class VillagePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('browse_villages');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Village $village): bool
    {
        return $user->hasPermission('read_villages');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('add_villages');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Village $village): bool
    {
        return $user->hasPermission('edit_villages');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Village $village): bool
    {
        return $user->hasPermission('delete_villages');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Village $village): bool
    {
        return $user->hasPermission('restore_villages');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Village $village): bool
    {
        return $user->hasPermission('forceDelete_villages');
    }
}
