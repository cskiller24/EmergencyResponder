<?php

namespace App\Policies;

use App\Models\User;

class InvitePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('invite_moderator');
    }

    /**
     * Determine whether the user can create models.
     */
    public function store(User $user): bool
    {
        return $user->hasPermissionTo('invite_moderator');
    }

    public function destroy(User $user): bool
    {
        return $user->hasPermissionTo('invite_moderator');
    }
}
