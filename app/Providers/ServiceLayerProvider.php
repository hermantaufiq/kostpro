<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ServiceLayerProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            \App\Contracts\Services\AuthServiceInterface::class,
            \App\Services\Auth\AuthService::class
        );
        $this->app->bind(
            \App\Contracts\Services\KamarServiceInterface::class,
            \App\Services\Kamar\KamarService::class
        );
        $this->app->bind(
            \App\Contracts\Services\GalleryServiceInterface::class,
            \App\Services\Gallery\GalleryService::class
        );
        $this->app->bind(
            \App\Contracts\Services\PenyewaanServiceInterface::class,
            \App\Services\Penyewaan\PenyewaanService::class
        );
        $this->app->bind(
            \App\Contracts\Services\TagihanServiceInterface::class,
            \App\Services\Tagihan\TagihanService::class
        );
        $this->app->bind(
            \App\Contracts\Services\PaymentServiceInterface::class,
            \App\Services\Payment\PaymentService::class
        );
    }

    public function boot(): void
    {
        //
    }
}
