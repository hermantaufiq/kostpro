<?php

namespace App\Services\Kamar;

use App\Contracts\Services\KamarServiceInterface;
use App\Contracts\Repositories\KamarRepositoryInterface;

class KamarService implements KamarServiceInterface
{
    public function __construct(
        private KamarRepositoryInterface $kamarRepository
    ) {}

    public function getListing(array $filters = [], int $perPage = 10)
    {
        return $this->kamarRepository->findAvailable($filters, $perPage);
    }

    public function getDetail($id)
    {
        return $this->kamarRepository->findWithGallery($id);
    }
}
