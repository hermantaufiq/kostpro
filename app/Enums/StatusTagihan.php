<?php

namespace App\Enums;

enum StatusTagihan: string
{
    case Unpaid    = 'unpaid';
    case Paid      = 'paid';
    case Overdue   = 'overdue';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::Unpaid    => 'Belum Dibayar',
            self::Paid      => 'Lunas',
            self::Overdue   => 'Jatuh Tempo',
            self::Cancelled => 'Dibatalkan',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::Unpaid    => 'bg-amber-100 text-amber-800',
            self::Paid      => 'bg-emerald-100 text-emerald-800',
            self::Overdue   => 'bg-red-100 text-red-800',
            self::Cancelled => 'bg-gray-100 text-gray-600',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Unpaid    => 'amber',
            self::Paid      => 'emerald',
            self::Overdue   => 'red',
            self::Cancelled => 'gray',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::Unpaid    => '⏳',
            self::Paid      => '✅',
            self::Overdue   => '🔴',
            self::Cancelled => '❌',
        };
    }

    public function isPaid(): bool
    {
        return $this === self::Paid;
    }

    public function isPayable(): bool
    {
        return in_array($this, [self::Unpaid, self::Overdue]);
    }
}
