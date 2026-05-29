<?php

namespace App\Contracts\Repositories;

interface TagihanRepositoryInterface extends BaseRepositoryInterface
{
    public function findByPenyewaan($penyewaanId);
    public function findOverdue();
    public function findUnpaid();
}
