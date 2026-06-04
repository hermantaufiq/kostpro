<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

use App\Models\Kamar;
use App\Models\Penyewaan;
use App\Models\Tagihan;

class KpiStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Kamar', Kamar::count())
                ->description('Kamar terisi: ' . Kamar::where('status', 'terisi')->count())
                ->descriptionIcon('heroicon-m-home')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
            Stat::make('Penyewa Aktif', Penyewaan::whereIn('status', ['pending', 'approved', 'active'])->count())
                ->description('Total pengajuan & sewa: ' . Penyewaan::count())
                ->descriptionIcon('heroicon-m-user-group')
                ->chart([2, 5, 4, 8, 5, 12, 10])
                ->color('primary'),
            Stat::make('Pendapatan Bulan Ini', 'Rp ' . number_format(Tagihan::where('status', 'paid')->whereMonth('tanggal_bayar', now()->month)->sum('total_tagihan'), 0, ',', '.'))
                ->description('Total tagihan pending: ' . Tagihan::where('status', 'unpaid')->count())
                ->descriptionIcon('heroicon-m-banknotes')
                ->chart([10, 15, 8, 20, 12, 25, 22])
                ->color('warning'),
        ];
    }
}
