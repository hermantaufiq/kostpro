<?php

namespace App\Enums;

enum StatusPembayaran: string
{
    case Pending  = 'pending';
    case Success  = 'success';
    case Failed   = 'failed';
    case Expired  = 'expired';
    case Refunded = 'refunded';

    public function label(): string
    {
        return match($this) {
            self::Pending  => 'Menunggu Pembayaran',
            self::Success  => 'Berhasil',
            self::Failed   => 'Gagal',
            self::Expired  => 'Kadaluarsa',
            self::Refunded => 'Dikembalikan',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::Pending  => 'bg-amber-100 text-amber-800',
            self::Success  => 'bg-emerald-100 text-emerald-800',
            self::Failed   => 'bg-red-100 text-red-800',
            self::Expired  => 'bg-gray-100 text-gray-700',
            self::Refunded => 'bg-blue-100 text-blue-800',
        };
    }

    public function isSuccess(): bool
    {
        return $this === self::Success;
    }

    public function isFinal(): bool
    {
        return in_array($this, [self::Success, self::Failed, self::Expired, self::Refunded]);
    }
}
