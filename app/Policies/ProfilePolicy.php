<?php

namespace App\Policies;

use App\Models\Profile;
use App\Models\User;

class ProfilePolicy
{
    /**
     * Determine whether the user can view the profile.
     */
    public function view(?User $user, Profile $profile): bool
    {
        $owner = $profile->user;

        if ($user && $user->id === $owner->id) {
            return true;
        }

        $visibility = $owner->profile_visibility ?? 'public';

        return match ($visibility) {
            'public' => true,
            'friends' => $this->isFriend($user, $owner),
            'private' => false,
            default => false,
        };
    }

    /**
     * Determine whether the user can update the profile.
     */
    public function update(?User $user, Profile $profile): bool
    {
        if (! $user) {
            return false;
        }

        return $user->id === $profile->user_id || $user->hasPermission('edit_users');
    }

    /**
     * Determine whether the user can delete the profile.
     */
    public function delete(?User $user, Profile $profile): bool
    {
        if (! $user) {
            return false;
        }

        return $user->id === $profile->user_id || $user->hasPermission('delete_users');
    }

    /**
     * Check whether two users are friends.
     *
     * TODO: Replace with actual friendship logic once available.
     */
    protected function isFriend(?User $user, ?User $owner): bool
    {
        if (! $user || ! $owner) {
            return false;
        }

        return $owner->friends?->contains($user->id) ?? false;
    }
}