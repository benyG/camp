<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UsersMail;
use Illuminate\Auth\Access\Response;

class UsersMailPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, UsersMail $usersMail): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, UsersMail $usersMail): bool
    {
        return $usersMail->user==$user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, UsersMail $usersMail): bool
    {
        return $usersMail->user==$user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, UsersMail $usersMail): bool
    {
        return false;

    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, UsersMail $usersMail): bool
    {
        return false;
    }
}