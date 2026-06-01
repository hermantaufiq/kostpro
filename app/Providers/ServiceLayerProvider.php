<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\Services\AuthServiceInterface;
use App\Services\Auth\AuthService;
use App\Contracts\Services\KamarServiceInterface;
use App\Services\Kamar\KamarService;
use App\Contracts\Services\GalleryServiceInterface;
use App\Services\Gallery\GalleryService;
use App\Contracts\Services\PenyewaanServiceInterface;
use App\Services\Penyewaan\PenyewaanService;
use App\Contracts\Services\TagihanServiceInterface;
use App\Services\Tagihan\TagihanService;
use App\Contracts\Services\PaymentServiceInterface;
use App\Services\Payment\PaymentService;
use App\Contracts\Services\CacheServiceInterface;
use App\Services\Cache\CacheService;

class ServiceLayerProvider extends ServiceProvider
{
    public array $bindings = [
        AuthServiceInterface::class => AuthService::class,
        KamarServiceInterface::class => KamarService::class,
        GalleryServiceInterface::class => GalleryService::class,
        PenyewaanServiceInterface::class => PenyewaanService::class,
        TagihanServiceInterface::class => TagihanService::class,
        PaymentServiceInterface::class => PaymentService::class,
        CacheServiceInterface::class => CacheService::class,
    ];

    public function register(): void
    {
        // Bindings are registered via $bindings property automatically in Laravel
    }

    public function boot(): void
    {
        //
    }
}
