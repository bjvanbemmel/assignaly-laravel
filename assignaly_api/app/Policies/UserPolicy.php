<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
    *   Check whether the authorized user can index all users.
    */
    public function can_index(): bool
    {
        return auth()->user()->role->level >= 2;
    }

    /**
    *   Check whether the authorized user can create a new user.
    */
    public function can_create(): bool
    {
        return auth()->user()->role->level >= 2;
    }

    /**
    *   Check whether the authorized user can update the given user.
    */
    public function can_update(User $user): bool
    {
        return auth()->user()->id === $user->id || auth()->user()->role->level >= 3;
    }

    /**
    *   Check whether the authorized user can view the given user.
    */
    public function can_view(User $user): bool
    {
        return auth()->user()->id === $user->id;
    }

    /**
    *   Check whether the authorized user can delete the given user.
    */
    public function can_delete(User $user): bool
    {
        return auth()->user()->id === $user->id;
    }
}
