<?php

namespace App\Policies;

use App\Models\RpsDocument;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RpsDocumentPolicy
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
    public function view(User $user, RpsDocument $rpsDocument): bool
    {
        if ($user->hasRole('admin') || $user->hasPermissionTo('view all rps')) {
            return true;
        }

        return $user->id === $rpsDocument->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('generate rps');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, RpsDocument $rpsDocument): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->id === $rpsDocument->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, RpsDocument $rpsDocument): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->id === $rpsDocument->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, RpsDocument $rpsDocument): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, RpsDocument $rpsDocument): bool
    {
        return $user->hasRole('admin');
    }
}
