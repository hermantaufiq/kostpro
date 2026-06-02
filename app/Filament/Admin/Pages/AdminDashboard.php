<?php

namespace App\Filament\Admin\Pages;

use App\Models\Kamar;
use App\Models\User;
use App\Models\Penyewaan;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use App\Services\KamarAvailabilityService;
use Filament\Pages\Dashboard;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;

class AdminDashboard extends Dashboard
{
    public function getHeading(): string
    {
        return 'Dashboard KosPro';
    }

    public function getWidgets(): array
    {
        return [
            AccountWidget::class,
            \App\Filament\Admin\Widgets\KpiWidget::class,
            \App\Filament\Admin\Widgets\RevenueChartWidget::class,
            \App\Filament\Admin\Widgets\OccupancyChartWidget::class,
            \App\Filament\Admin\Widgets\PaymentMethodChartWidget::class,
            \App\Filament\Admin\Widgets\TenantGrowthChartWidget::class,
        ];
    }
}
