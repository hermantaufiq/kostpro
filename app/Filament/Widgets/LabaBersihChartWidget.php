<?php

namespace App\Filament\Widgets;

use App\Models\Pembayaran;
use App\Models\Pengeluaran;
use App\Enums\StatusPembayaran;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class LabaBersihChartWidget extends ChartWidget
{

    public function getHeading(): ?string
    {
        return 'Pemasukan vs Pengeluaran (6 Bulan)';
    }

    public static function getSort(): int
    {
        return 6;
    }

    protected function getData(): array
    {
        $pemasukan = [];
        $pengeluaran = [];
        $laba = [];
        $labels = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $labels[] = $month->format('M Y');

            $in = Pembayaran::where('status', StatusPembayaran::Success)
                ->whereMonth('paid_at', $month->month)
                ->whereYear('paid_at', $month->year)
                ->sum('jumlah_diterima') / 1000000;

            $out = Pengeluaran::whereMonth('tanggal', $month->month)
                ->whereYear('tanggal', $month->year)
                ->sum('jumlah') / 1000000;

            $pemasukan[]   = round($in, 2);
            $pengeluaran[] = round($out, 2);
            $laba[]        = round($in - $out, 2);
        }

        return [
            'datasets' => [
                [
                    'label'           => 'Pemasukan (Juta Rp)',
                    'data'            => $pemasukan,
                    'borderColor'     => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.15)',
                    'borderWidth'     => 2,
                    'tension'         => 0.4,
                    'fill'            => true,
                ],
                [
                    'label'           => 'Pengeluaran (Juta Rp)',
                    'data'            => $pengeluaran,
                    'borderColor'     => '#ef4444',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.1)',
                    'borderWidth'     => 2,
                    'tension'         => 0.4,
                    'fill'            => true,
                ],
                [
                    'label'           => 'Laba Bersih (Juta Rp)',
                    'data'            => $laba,
                    'borderColor'     => '#6366f1',
                    'backgroundColor' => 'rgba(99, 102, 241, 0.1)',
                    'borderWidth'     => 2,
                    'tension'         => 0.4,
                    'fill'            => false,
                    'borderDash'      => [5, 5],
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
