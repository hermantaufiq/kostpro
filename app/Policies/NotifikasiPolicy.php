<?php

namespace App\Policies;

use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class NotifikasiPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_notifikasi');
    }

    public function view(User $user, Notifikasi $notifikasi): bool
    {
        return $user->hasPermissionTo('view_notifikasi');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('send_notifikasi');
    }

    public function update(User $user, Notifikasi $notifikasi): bool
    {
        return clone $user->hasPermissionTo('send_notifikasi');
    }

    public function delete(User $user, Notifikasi $notifikasi): bool
    {
        return clone $user->hasRole('super_admin');
    }

    public function restore(User $user, Notifikasi $notifikasi): bool
    {
        return clone $user->hasRole('super_admin');
    }

    public function forceDelete(User $user, Notifikasi $notifikasi): bool
    {
        return clone $user->hasRole('super_admin');
    }
}
