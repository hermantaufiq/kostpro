<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            \App\Contracts\Repositories\KamarRepositoryInterface::class,
            \App\Repositories\Eloquent\KamarRepository::class
        );
        $this->app->bind(
            \App\Contracts\Repositories\PenyewaanRepositoryInterface::class,
            \App\Repositories\Eloquent\PenyewaanRepository::class
        );
        $this->app->bind(
            \App\Contracts\Repositories\TagihanRepositoryInterface::class,
            \App\Repositories\Eloquent\TagihanRepository::class
        );
        $this->app->bind(
            \App\Contracts\Repositories\PembayaranRepositoryInterface::class,
            \App\Repositories\Eloquent\PembayaranRepository::class
        );
        $this->app->bind(
            \App\Contracts\Repositories\UserRepositoryInterface::class,
            \App\Repositories\Eloquent\UserRepository::class
        );
    }

    public function boot(): void
    {
        //
    }
}
