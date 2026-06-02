<?php

namespace App\Filament\Admin\Widgets;

use App\Models\User;
use App\Models\Penyewaan;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class TenantGrowthChartWidget extends ChartWidget
{
    public function getHeading(): ?string
    {
        return 'Pertumbuhan Penyewa (12 Bulan Terakhir)';
    }

    public static function getSort(): int
    {
        return 5;
    }

    protected function getData(): array
    {
        $data = [];
        $labels = [];

        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $labels[] = $month->format('M Y');

            $count = User::where('user_type', 'penyewa')
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->count();

            $data[] = $count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Penyewa Baru',
                    'data' => $data,
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
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
