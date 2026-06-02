<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Pembayaran;
use App\Enums\StatusPembayaran;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class RevenueChartWidget extends ChartWidget
{
    public function getHeading(): ?string
    {
        return 'Pendapatan Harian (30 Hari Terakhir)';
    }

    public static function getSort(): int
    {
        return 2;
    }

    protected function getData(): array
    {
        $data = [];
        $labels = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();
            $labels[] = $date->format('d M');

            $revenue = Pembayaran::where('status', StatusPembayaran::Lunas)
                ->whereDate('paid_at', $date)
                ->sum('jumlah_diterima');

            $data[] = $revenue / 1000000; // Convert to millions for readability
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pendapatan (Juta Rp)',
                    'data' => $data,
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'borderWidth' => 2,
                    'tension' => 0.4,
                    'fill' => true,
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
