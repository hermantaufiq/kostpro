<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Kamar;

class OccupancyWidget extends ChartWidget
{
    protected static ?string $heading = 'Tingkat Keterisian Kamar (Occupancy)';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $terisi = Kamar::where('status', 'terisi')->count();
        $tersedia = Kamar::where('status', 'tersedia')->count();
        $maintenance = Kamar::where('status', 'maintenance')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Kamar',
                    'data' => [$terisi, $tersedia, $maintenance],
                    'backgroundColor' => [
                        '#3b82f6', // blue
                        '#22c55e', // green
                        '#eab308', // yellow
                    ],
                ],
            ],
            'labels' => ['Terisi', 'Tersedia', 'Maintenance'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
