<?php

namespace App\Filament\Widgets;

use App\Models\Kamar;
use App\Models\Penyewaan;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class LaporanStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $tahunIni = now()->year;
        $bulanIni = now()->month;

        $totalKamar    = Kamar::count();
        $kamarTerisi   = Kamar::where('status', 'terisi')->count();
        $occupancyRate = $totalKamar > 0 ? round(($kamarTerisi / $totalKamar) * 100) : 0;

        $pendapatanBulanIni = Pembayaran::query()
            ->whereYear('paid_at', $tahunIni)
            ->whereMonth('paid_at', $bulanIni)
            ->where('status', 'success')
            ->sum('jumlah_diterima');

        $totalTunggakan = Tagihan::whereIn('status', ['unpaid', 'overdue'])->sum('total_tagihan');
        $totalPenyewaan = Penyewaan::whereIn('status', ['active', 'approved'])->count();

        return [
            Stat::make('Occupancy Rate', $occupancyRate . '%')
                ->description($kamarTerisi . ' dari ' . $totalKamar . ' slot terisi')
                ->descriptionIcon('heroicon-m-home-modern')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
                
            Stat::make('Pendapatan Bulan Ini', 'Rp ' . number_format($pendapatanBulanIni, 0, ',', '.'))
                ->description('Pemasukan bulan ' . Carbon::now()->isoFormat('MMMM Y'))
                ->descriptionIcon('heroicon-m-banknotes')
                ->chart([2, 5, 3, 8, 4, 9, 10])
                ->color('primary'),
                
            Stat::make('Total Tunggakan', 'Rp ' . number_format($totalTunggakan, 0, ',', '.'))
                ->description('Tagihan yang belum dibayar')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->chart([10, 8, 5, 2, 6, 9, 12])
                ->color('danger'),
                
            Stat::make('Penyewa Aktif', $totalPenyewaan)
                ->description('Total penyewa dengan kontrak berjalan')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),
        ];
    }
}
