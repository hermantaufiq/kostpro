<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Kamar;
use App\Enums\StatusKamar;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class OccupancyChartWidget extends ChartWidget
{
    public function getHeading(): ?string
    {
        return 'Tingkat Okupansi (30 Hari Terakhir)';
    }

    public static function getSort(): int
    {
        return 3;
    }

    protected function getData(): array
    {
        $data = [];
        $labels = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();
            $labels[] = $date->format('d M');

            $totalKamar = Kamar::count();
            $occupiedKamar = Kamar::where('status', StatusKamar::Terisi)->count();
            
            $occupancy = $totalKamar > 0 ? round(($occupiedKamar / $totalKamar) * 100, 1) : 0;
            $data[] = $occupancy;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Okupansi (%)',
                    'data' => $data,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
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
