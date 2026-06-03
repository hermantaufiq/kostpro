<?php

namespace App\Enums;

enum StatusKeluhan: string
{
    case Menunggu = 'menunggu';
    case Diproses = 'diproses';
    case Selesai = 'selesai';
    case Ditolak = 'ditolak';

    public function label(): string
    {
        return match($this) {
            self::Menunggu => 'Menunggu',
            self::Diproses => 'Sedang Diproses',
            self::Selesai => 'Selesai',
            self::Ditolak => 'Ditolak',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Menunggu => 'amber',
            self::Diproses => 'blue',
            self::Selesai => 'emerald',
            self::Ditolak => 'rose',
        };
    }
}
