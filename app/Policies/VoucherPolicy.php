<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Voucher;

class VoucherPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_voucher');
    }

    public function view(User $user, Voucher $voucher): bool
    {
        return $user->hasPermissionTo('view_voucher');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_voucher');
    }

    public function update(User $user, Voucher $voucher): bool
    {
        return $user->hasPermissionTo('update_voucher');
    }

    public function delete(User $user, Voucher $voucher): bool
    {
        return $user->hasRole('super_admin');
    }

    public function restore(User $user, Voucher $voucher): bool
    {
        return $user->hasRole('super_admin');
    }

    public function forceDelete(User $user, Voucher $voucher): bool
    {
        return $user->hasRole('super_admin');
    }
}
