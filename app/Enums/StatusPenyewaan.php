<?php

namespace App\Enums;

enum StatusPenyewaan: string
{
    case Pending   = 'pending';
    case Approved  = 'approved';
    case Rejected  = 'rejected';
    case Active    = 'active';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::Pending   => 'Menunggu Persetujuan',
            self::Approved  => 'Disetujui',
            self::Rejected  => 'Ditolak',
            self::Active    => 'Aktif',
            self::Completed => 'Selesai',
            self::Cancelled => 'Dibatalkan',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Pending   => 'amber',
            self::Approved  => 'blue',
            self::Rejected  => 'red',
            self::Active    => 'emerald',
            self::Completed => 'slate',
            self::Cancelled => 'gray',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::Pending   => 'bg-amber-100 text-amber-800',
            self::Approved  => 'bg-blue-100 text-blue-800',
            self::Rejected  => 'bg-red-100 text-red-800',
            self::Active    => 'bg-emerald-100 text-emerald-800',
            self::Completed => 'bg-slate-100 text-slate-700',
            self::Cancelled => 'bg-gray-100 text-gray-600',
        };
    }

    public function isActive(): bool
    {
        return $this === self::Active;
    }

    public function canBeCancelled(): bool
    {
        return in_array($this, [self::Pending, self::Approved]);
    }

    public function canBeExtended(): bool
    {
        return $this === self::Active;
    }
}
