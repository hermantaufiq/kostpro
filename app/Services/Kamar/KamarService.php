<?php

namespace App\Services\Kamar;

use App\Contracts\Services\KamarServiceInterface;
use App\Contracts\Repositories\KamarRepositoryInterface;
use App\Contracts\Services\CacheServiceInterface;

class KamarService implements KamarServiceInterface
{
    public function __construct(
        private KamarRepositoryInterface $kamarRepository,
        private CacheServiceInterface $cacheService
    ) {}

    public function getListing(array $filters = [], int $perPage = 10)
    {
        $cacheKey = 'kamar_listing_' . md5(json_encode($filters) . '_' . $perPage . '_' . request('page', 1));
        
        return $this->cacheService->remember($cacheKey, ['kamar_list'], function () use ($filters, $perPage) {
            return $this->kamarRepository->findAvailable($filters, $perPage);
        }, 3600);
    }

    public function getDetail($id)
    {
        $cacheKey = "kamar_detail_{$id}";

        return $this->cacheService->remember($cacheKey, ["kamar_detail_{$id}"], function () use ($id) {
            return $this->kamarRepository->findWithGallery($id);
        }, 3600);
    }
}
