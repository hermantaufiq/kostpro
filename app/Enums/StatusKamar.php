<?php

namespace App\Enums;

enum StatusKamar: string
{
    case Tersedia    = 'tersedia';
    case Terisi      = 'terisi';
    case Maintenance = 'maintenance';
    case Reserved    = 'reserved';

    public function label(): string
    {
        return match($this) {
            self::Tersedia    => 'Tersedia',
            self::Terisi      => 'Terisi',
            self::Maintenance => 'Maintenance',
            self::Reserved    => 'Reserved',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::Tersedia    => 'badge-available',
            self::Terisi      => 'badge-occupied',
            self::Maintenance => 'badge-maintenance',
            self::Reserved    => 'badge-reserved',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Tersedia    => 'emerald',
            self::Terisi      => 'red',
            self::Maintenance => 'amber',
            self::Reserved    => 'blue',
        };
    }

    public function isAvailable(): bool
    {
        return $this === self::Tersedia;
    }
}
