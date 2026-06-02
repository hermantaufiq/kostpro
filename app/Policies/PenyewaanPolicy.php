<?php

namespace App\Policies;

use App\Models\Penyewaan;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PenyewaanPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_penyewaan');
    }

    public function view(User $user, Penyewaan $penyewaan): bool
    {
        return $user->hasPermissionTo('view_penyewaan');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_penyewaan');
    }

    public function update(User $user, Penyewaan $penyewaan): bool
    {
        return $user->hasPermissionTo('update_penyewaan');
    }

    public function delete(User $user, Penyewaan $penyewaan): bool
    {
        return $user->hasPermissionTo('delete_penyewaan');
    }

    public function restore(User $user, Penyewaan $penyewaan): bool
    {
        return $user->hasRole('super_admin');
    }

    public function forceDelete(User $user, Penyewaan $penyewaan): bool
    {
        return $user->hasRole('super_admin');
    }
}
