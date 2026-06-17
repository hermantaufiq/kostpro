<?php

namespace App\Enums;

enum StatusVoucher: string
{
    case Aktif      = 'aktif';
    case Digunakan  = 'digunakan';
    case Kadaluarsa = 'kadaluarsa';

    public function label(): string
    {
        return match ($this) {
            self::Aktif      => 'Aktif',
            self::Digunakan  => 'Digunakan',
            self::Kadaluarsa => 'Kadaluarsa',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Aktif      => 'success',
            self::Digunakan  => 'info',
            self::Kadaluarsa => 'danger',
        };
    }
}
