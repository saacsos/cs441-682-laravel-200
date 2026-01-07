<?php

namespace App\Policies;

use App\Models\Artist;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ArtistPolicy
{
    /**
     * @param User $user : Authenticated user
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Any authenticated user can `viewAny` artist
    }

    /**
     * @param User $user : Authenticated user
     * @param Artist $artist : The specific artist that user can view or not
     * Determine whether the user can view the model.
     */
    public function view(User $user, Artist $artist): bool
    {
        return true; // Any authenticated user can `view` the specific artist
    }

    /**
     * @param User $user : Authenticated user
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin(); // Only user with admin role can `create` a new artist
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Artist $artist): bool
    {
        return $user->isAdmin(); // Only user with admin role can `update` that artist
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Artist $artist): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Artist $artist): bool
    {
        return false; // No one can `restore` that artist
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Artist $artist): bool
    {
        return false; // No one can `forceDelete` that artist
    }
}
