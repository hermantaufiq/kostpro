<?php

namespace App\Enums;

enum KategoriPengeluaran: string
{
    case Listrik = 'listrik';
    case Air = 'air';
    case Internet = 'internet';
    case Kebersihan = 'kebersihan';
    case Perbaikan = 'perbaikan';
    case Gaji = 'gaji';
    case Lainnya = 'lainnya';

    public function label(): string
    {
        return match($this) {
            self::Listrik => 'Tagihan Listrik',
            self::Air => 'Tagihan Air',
            self::Internet => 'Langganan Internet',
            self::Kebersihan => 'Biaya Kebersihan',
            self::Perbaikan => 'Perbaikan & Maintenance',
            self::Gaji => 'Gaji Pegawai',
            self::Lainnya => 'Lain-lain',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Listrik => 'amber',
            self::Air => 'blue',
            self::Internet => 'indigo',
            self::Kebersihan => 'emerald',
            self::Perbaikan => 'rose',
            self::Gaji => 'violet',
            self::Lainnya => 'gray',
        };
    }
}
