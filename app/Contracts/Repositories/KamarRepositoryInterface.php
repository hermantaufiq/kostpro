<?php

namespace App\Contracts\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;

interface KamarRepositoryInterface extends BaseRepositoryInterface
{
    public function findAvailable(array $filters = [], int $perPage = 10): LengthAwarePaginator;
    public function findWithGallery($id);
}
