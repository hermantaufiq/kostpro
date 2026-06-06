<?php

namespace App\Filament\Widgets;

use App\Models\Pembayaran;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PendapatanChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Pendapatan Bulanan';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $tahunIni = now()->year;
        
        $revenuePerBulan = Pembayaran::query()
            ->whereYear('paid_at', $tahunIni)
            ->where('status', 'success')
            ->select(DB::raw('MONTH(paid_at) as bulan'), DB::raw('SUM(jumlah_diterima) as total'))
            ->groupBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        $data = [];
        for ($i = 1; $i <= 12; $i++) {
            $data[] = $revenuePerBulan[$i] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pendapatan (Rp)',
                    'data' => $data,
                    'backgroundColor' => '#4f46e5', // Indigo 600
                    'borderColor' => '#4f46e5',
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
