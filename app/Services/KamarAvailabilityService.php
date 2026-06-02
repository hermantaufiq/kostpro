<?php

namespace App\Services;

use App\Models\Kamar;
use App\Models\Tagihan;
use App\Enums\StatusKamar;

class KamarAvailabilityService
{
    /**
     * Check room availability for given date range
     */
    public static function isAvailable(Kamar $kamar, \DateTime $startDate, \DateTime $endDate): bool
    {
        $overlappingRentals = $kamar->penyewaan()
            ->whereNotNull('tanggal_masuk')
            ->whereBetween('tanggal_masuk', [$startDate, $endDate])
            ->orWhereBetween('tanggal_keluar', [$startDate, $endDate])
            ->count();

        return $overlappingRentals === 0;
    }

    /**
     * Get kamar occupancy percentage
     */
    public static function getOccupancyRate(): float
    {
        $total = Kamar::count();
        if ($total === 0) return 0;

        $occupied = Kamar::where('status', StatusKamar::Terisi)->count();
        return round(($occupied / $total) * 100, 2);
    }

    /**
     * Get available kamar count
     */
    public static function getAvailableCount(): int
    {
        return Kamar::where('status', StatusKamar::Tersedia)->count();
    }

    /**
     * Get occupied kamar count
     */
    public static function getOccupiedCount(): int
    {
        return Kamar::where('status', StatusKamar::Terisi)->count();
    }
}
