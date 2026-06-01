<?php

namespace App\Observers;

use App\Models\Kamar;
use App\Contracts\Services\CacheServiceInterface;

class KamarObserver
{
    public function __construct(
        private CacheServiceInterface $cacheService
    ) {}

    public function created(Kamar $kamar): void
    {
        $this->cacheService->invalidateTags(['kamar_list']);
    }

    public function updated(Kamar $kamar): void
    {
        $this->cacheService->invalidateTags(['kamar_list', "kamar_detail_{$kamar->id}"]);
    }

    public function deleted(Kamar $kamar): void
    {
        $this->cacheService->invalidateTags(['kamar_list', "kamar_detail_{$kamar->id}"]);
    }

    public function restored(Kamar $kamar): void
    {
        $this->cacheService->invalidateTags(['kamar_list', "kamar_detail_{$kamar->id}"]);
    }

    public function forceDeleted(Kamar $kamar): void
    {
        $this->cacheService->invalidateTags(['kamar_list', "kamar_detail_{$kamar->id}"]);
    }
}
