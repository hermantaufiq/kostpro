<?php

namespace App\Contracts\Services;

interface KamarServiceInterface
{
    public function getListing(array $filters = [], int $perPage = 10);
    public function getDetail($id);
}
