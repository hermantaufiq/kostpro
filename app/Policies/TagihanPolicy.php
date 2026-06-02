<?php

namespace App\Policies;

use App\Models\Tagihan;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TagihanPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_tagihan');
    }

    public function view(User $user, Tagihan $tagihan): bool
    {
        return $user->hasPermissionTo('view_tagihan');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_tagihan');
    }

    public function update(User $user, Tagihan $tagihan): bool
    {
        return $user->hasPermissionTo('update_tagihan');
    }

    public function delete(User $user, Tagihan $tagihan): bool
    {
        return $user->hasPermissionTo('delete_tagihan');
    }

    public function restore(User $user, Tagihan $tagihan): bool
    {
        return $user->hasRole('super_admin');
    }

    public function forceDelete(User $user, Tagihan $tagihan): bool
    {
        return $user->hasRole('super_admin');
    }
}
