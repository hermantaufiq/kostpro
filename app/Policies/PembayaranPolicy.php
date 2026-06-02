<?php

namespace App\Policies;

use App\Models\Pembayaran;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PembayaranPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_pembayaran');
    }

    public function view(User $user, Pembayaran $pembayaran): bool
    {
        return $user->hasPermissionTo('view_pembayaran');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('mark_pembayaran');
    }

    public function update(User $user, Pembayaran $pembayaran): bool
    {
        return $user->hasPermissionTo('mark_pembayaran');
    }

    public function delete(User $user, Pembayaran $pembayaran): bool
    {
        return $user->hasRole('super_admin');
    }

    public function restore(User $user, Pembayaran $pembayaran): bool
    {
        return $user->hasRole('super_admin');
    }

    public function forceDelete(User $user, Pembayaran $pembayaran): bool
    {
        return $user->hasRole('super_admin');
    }
}
