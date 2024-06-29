<?php

namespace App\Policies;

use App\Models\Chx;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ChxPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->ex == 0;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Chx $chx): bool
    {
        return $user->ex == 0;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Chx $chx): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Chx $chx): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Chx $chx): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Chx $chx): bool
    {
        return false;
    }
}