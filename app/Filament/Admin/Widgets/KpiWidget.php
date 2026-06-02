<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Kamar;
use App\Models\User;
use App\Models\Penyewaan;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use App\Enums\StatusKamar;
use App\Enums\StatusTagihan;
use App\Enums\StatusPembayaran;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class KpiWidget extends BaseWidget
{
    protected ?string $pollingInterval = '30s';

    public static function getSort(): int
    {
        return 1;
    }

    protected function getStats(): array
    {
        $totalKamar   = Kamar::count();
        $kamarTerisi  = Kamar::where('status', StatusKamar::Terisi)->count();
        $kamarKosong  = Kamar::where('status', StatusKamar::Tersedia)->count();

        $occupancyRate = $totalKamar > 0
            ? round(($kamarTerisi / $totalKamar) * 100, 1)
            : 0;

        $totalPenyewa  = User::where('user_type', 'tenant')->count();
        $penyewaAktif  = Penyewaan::where('status', 'active')->count();

        $tagihanAktif      = Tagihan::whereIn('status', [StatusTagihan::Unpaid, StatusTagihan::Overdue])->count();
        $tagihanJatuhTempo = Tagihan::where('status', StatusTagihan::Overdue)->count();

        $totalPendapatan = Pembayaran::where('status', StatusPembayaran::Success)
            ->sum('jumlah_diterima');

        $pendapatanBulanIni = Pembayaran::where('status', StatusPembayaran::Success)
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('jumlah_diterima');

        $pendapatanHariIni = Pembayaran::where('status', StatusPembayaran::Success)
            ->whereDate('paid_at', today())
            ->sum('jumlah_diterima');

        return [
            Stat::make('Total Kamar', $totalKamar)
                ->description('Semua unit kamar')
                ->color('primary')
                ->icon('heroicon-o-home-modern')
                ->chart($this->getLast7DaysKamarChart()),

            Stat::make('Kamar Terisi', $kamarTerisi)
                ->description("Okupansi: {$occupancyRate}%")
                ->color('success')
                ->icon('heroicon-o-check-circle')
                ->chart($this->getOccupancyTrend()),

            Stat::make('Kamar Kosong', $kamarKosong)
                ->description('Siap disewa')
                ->color('warning')
                ->icon('heroicon-o-inbox')
                ->chart($this->getAvailableTrend()),

            Stat::make('Total Penyewa', $totalPenyewa)
                ->description('Terdaftar di sistem')
                ->color('info')
                ->icon('heroicon-o-users')
                ->chart($this->getTenantGrowthChart()),

            Stat::make('Penyewa Aktif', $penyewaAktif)
                ->description('Sedang menempati kamar')
                ->color('success')
                ->icon('heroicon-o-user-check')
                ->chart($this->getActiveTenantTrend()),

            Stat::make('Tagihan Aktif', $tagihanAktif)
                ->description('Belum lunas')
                ->color($tagihanAktif > 0 ? 'warning' : 'success')
                ->icon('heroicon-o-document-text')
                ->chart($this->getUnpaidInvoiceTrend()),

            Stat::make('Tagihan Jatuh Tempo', $tagihanJatuhTempo)
                ->description('Perlu tindakan segera')
                ->color($tagihanJatuhTempo > 0 ? 'danger' : 'success')
                ->icon('heroicon-o-exclamation-circle')
                ->chart($this->getOverdueTrend()),

            Stat::make('Total Pendapatan', 'Rp ' . number_format($totalPendapatan, 0, ',', '.'))
                ->description('Sejak beroperasi')
                ->color('success')
                ->icon('heroicon-o-banknotes')
                ->chart($this->getRevenueTrend()),

            Stat::make('Pendapatan Bulan Ini', 'Rp ' . number_format($pendapatanBulanIni, 0, ',', '.'))
                ->description('Bulan ' . now()->translatedFormat('F Y'))
                ->color('success')
                ->icon('heroicon-o-calendar')
                ->chart($this->getMonthlyRevenueTrend()),

            Stat::make('Pendapatan Hari Ini', 'Rp ' . number_format($pendapatanHariIni, 0, ',', '.'))
                ->description(now()->translatedFormat('d M Y'))
                ->color('success')
                ->icon('heroicon-o-clock')
                ->chart($this->getDailyRevenueTrend()),
        ];
    }

    // ------- Real Chart Data -------

    private function getLast7DaysKamarChart(): array
    {
        // Kamar count is static — show flat trend
        $total = Kamar::count();
        return array_fill(0, 7, $total);
    }

    private function getOccupancyTrend(): array
    {
        return $this->getLast7DaysTrend(
            fn($date) => Penyewaan::where('status', 'active')
                ->whereDate('tanggal_masuk', '<=', $date)
                ->count()
        );
    }

    private function getAvailableTrend(): array
    {
        $total = Kamar::count();
        return array_map(
            fn($v) => max(0, $total - $v),
            $this->getOccupancyTrend()
        );
    }

    private function getTenantGrowthChart(): array
    {
        return $this->getLast7DaysTrend(
            fn($date) => User::where('user_type', 'tenant')
                ->whereDate('created_at', '<=', $date)
                ->count()
        );
    }

    private function getActiveTenantTrend(): array
    {
        return $this->getLast7DaysTrend(
            fn($date) => Penyewaan::where('status', 'active')
                ->whereDate('created_at', '<=', $date)
                ->count()
        );
    }

    private function getUnpaidInvoiceTrend(): array
    {
        return $this->getLast7DaysTrend(
            fn($date) => Tagihan::whereIn('status', [StatusTagihan::Unpaid, StatusTagihan::Overdue])
                ->whereDate('created_at', '<=', $date)
                ->count()
        );
    }

    private function getOverdueTrend(): array
    {
        return $this->getLast7DaysTrend(
            fn($date) => Tagihan::where('status', StatusTagihan::Overdue)
                ->whereDate('created_at', '<=', $date)
                ->count()
        );
    }

    private function getRevenueTrend(): array
    {
        return $this->getLast7DaysTrend(
            fn($date) => (int) (Pembayaran::where('status', StatusPembayaran::Success)
                ->whereDate('paid_at', '<=', $date)
                ->sum('jumlah_diterima') / 1_000_000)
        );
    }

    private function getMonthlyRevenueTrend(): array
    {
        return $this->getLast7DaysTrend(
            fn($date) => (int) (Pembayaran::where('status', StatusPembayaran::Success)
                ->whereMonth('paid_at', now()->month)
                ->whereYear('paid_at', now()->year)
                ->whereDate('paid_at', '<=', $date)
                ->sum('jumlah_diterima') / 1_000_000)
        );
    }

    private function getDailyRevenueTrend(): array
    {
        return $this->getLast7DaysTrend(
            fn($date) => (int) (Pembayaran::where('status', StatusPembayaran::Success)
                ->whereDate('paid_at', $date)
                ->sum('jumlah_diterima') / 1_000_000)
        );
    }

    /**
     * Helper: generate last 7 days data points using a callback
     */
    private function getLast7DaysTrend(\Closure $callback): array
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date   = Carbon::today()->subDays($i)->toDateString();
            $data[] = $callback($date);
        }
        return $data;
    }
}
