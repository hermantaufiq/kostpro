<?php

namespace App\Contracts\Repositories;

interface PenyewaanRepositoryInterface extends BaseRepositoryInterface
{
    public function findByUser($userId, int $perPage = 10);
    public function getActiveByKamar($kamarId);
}
