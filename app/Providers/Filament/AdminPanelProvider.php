<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Http\Middleware\AdminOnly;
use App\Http\Middleware\LogAdminActivity;
use App\Filament\Admin\Resources\RoomResource;
use App\Filament\Admin\Resources\TenantResource;
use App\Filament\Admin\Resources\RentalApplicationResource;
use App\Filament\Admin\Resources\InvoiceResource;
use App\Filament\Admin\Resources\PaymentResource;
use App\Filament\Admin\Resources\NotificationResource;
use App\Filament\Admin\Resources\ReportResource;
use App\Filament\Admin\Widgets\KpiWidget;
use App\Filament\Admin\Widgets\RevenueChartWidget;
use App\Filament\Admin\Widgets\OccupancyChartWidget;
use App\Filament\Admin\Widgets\PaymentMethodChartWidget;
use App\Filament\Admin\Widgets\TenantGrowthChartWidget;
use App\Filament\Admin\Widgets\RecentActivityWidget;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->sidebarCollapsibleOnDesktop()
            ->brandName('KosPro Admin')
            ->brandLogo(asset('images/logo.png'))
            ->colors([
                'primary' => Color::Blue,
                'danger' => Color::Rose,
                'gray' => Color::Slate,
                'info' => Color::Blue,
                'success' => Color::Emerald,
                'warning' => Color::Amber,
            ])
            ->font('Inter')
            ->favicon(asset('images/favicon.ico'))
            ->unsavedChangesAlerts()
            ->spa()
            ->resources([
                RoomResource::class,
                TenantResource::class,
                RentalApplicationResource::class,
                InvoiceResource::class,
                PaymentResource::class,
                NotificationResource::class,
                ReportResource::class,
            ])
            ->pages([
                \App\Filament\Admin\Pages\AdminDashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\Filament\Admin\Widgets')
            ->widgets([
                KpiWidget::class,
                RevenueChartWidget::class,
                OccupancyChartWidget::class,
                PaymentMethodChartWidget::class,
                TenantGrowthChartWidget::class,
                RecentActivityWidget::class,
                AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                AdminOnly::class,
                LogAdminActivity::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
