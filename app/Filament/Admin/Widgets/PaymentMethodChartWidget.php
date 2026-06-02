<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Pembayaran;
use App\Enums\StatusPembayaran;
use App\Enums\MetodePembayaran;
use Filament\Widgets\ChartWidget;

class PaymentMethodChartWidget extends ChartWidget
{
    public function getHeading(): ?string
    {
        return 'Pembayaran Berdasarkan Metode';
    }

    public static function getSort(): int
    {
        return 4;
    }

    protected function getData(): array
    {
        $methods = [];
        $colors = ['#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6'];

        foreach (MetodePembayaran::cases() as $index => $method) {
            $count = Pembayaran::where('metode', $method)->count();
            $methods[$method->label()] = $count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Pembayaran',
                    'data' => array_values($methods),
                    'backgroundColor' => $colors,
                    'borderColor' => $colors,
                    'borderWidth' => 1,
                ],
            ],
            'labels' => array_keys($methods),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
