<?php

namespace App\Filament\Pages;

use App\Models\Kamar;
use App\Models\Penyewaan;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class LaporanPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $navigationLabel = 'Laporan';
    protected static ?string $title = 'Laporan Keuangan & Hunian';
    protected static ?int $navigationSort = 10;

    protected string $view = 'filament.pages.laporan-page';

    public function getViewData(): array
    {
        $tahunIni = now()->year;
        $bulanIni = now()->month;

        // Revenue per month (this year)
        $revenuePerBulan = Pembayaran::query()
            ->whereYear('paid_at', $tahunIni)
            ->where('status', 'success')
            ->select(
                DB::raw('MONTH(paid_at) as bulan'),
                DB::raw('SUM(jumlah_diterima) as total')
            )
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        // Build 12-month array
        $pendapatanBulanan = [];
        for ($i = 1; $i <= 12; $i++) {
            $pendapatanBulanan[$i] = $revenuePerBulan[$i] ?? 0;
        }

        // KPI Stats
        $totalKamar       = Kamar::count();
        $kamarTerisi      = Kamar::where('status', 'terisi')->count();
        $kamarTersedia    = Kamar::where('status', 'tersedia')->count();
        $occupancyRate    = $totalKamar > 0 ? round(($kamarTerisi / $totalKamar) * 100) : 0;

        $pendapatanBulanIni = Pembayaran::query()
            ->whereYear('paid_at', $tahunIni)
            ->whereMonth('paid_at', $bulanIni)
            ->where('status', 'success')
            ->sum('jumlah_diterima');

        $totalTunggakan = Tagihan::where('status', 'unpaid')
            ->orWhere('status', 'overdue')
            ->sum('total_tagihan');

        $totalPenyewaan = Penyewaan::where('status', 'active')->count();

        // Tagihan tunggakan detail
        $tunggakan = Tagihan::with(['user', 'penyewaan.kamar'])
            ->whereIn('status', ['unpaid', 'overdue'])
            ->orderBy('tanggal_jatuh_tempo')
            ->limit(10)
            ->get();

        // Penyewaan aktif
        $penyewaanAktif = Penyewaan::with(['user', 'kamar'])
            ->where('status', 'active')
            ->orderBy('tanggal_masuk')
            ->limit(10)
            ->get();

        return [
            'totalKamar'          => $totalKamar,
            'kamarTerisi'         => $kamarTerisi,
            'kamarTersedia'       => $kamarTersedia,
            'occupancyRate'       => $occupancyRate,
            'pendapatanBulanIni'  => $pendapatanBulanIni,
            'totalTunggakan'      => $totalTunggakan,
            'totalPenyewaan'      => $totalPenyewaan,
            'pendapatanBulanan'   => $pendapatanBulanan,
            'tunggakan'           => $tunggakan,
            'penyewaanAktif'      => $penyewaanAktif,
            'tahunIni'            => $tahunIni,
            'bulanIni'            => $bulanIni,
        ];
    }
}
