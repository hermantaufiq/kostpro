<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Tagihan;
use Carbon\Carbon;

class RevenueChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Pendapatan 6 Bulan Terakhir';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data = [];
        $labels = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $labels[] = $month->translatedFormat('M Y');
            
            $data[] = Tagihan::where('status', 'paid')
                ->whereYear('tanggal_bayar', $month->year)
                ->whereMonth('tanggal_bayar', $month->month)
                ->sum('total_tagihan');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pendapatan (Rp)',
                    'data' => $data,
                    'fill' => 'start',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'tension' => 0.3,
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
