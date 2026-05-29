<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\PenyewaanRepositoryInterface;
use App\Models\Penyewaan;
use App\Enums\StatusPenyewaan;

class PenyewaanRepository extends BaseRepository implements PenyewaanRepositoryInterface
{
    public function __construct(Penyewaan $model)
    {
        parent::__construct($model);
    }

    public function findByUser($userId, int $perPage = 10)
    {
        return $this->model->where('user_id', $userId)
            ->with(['kamar', 'tagihan'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function getActiveByKamar($kamarId)
    {
        return $this->model->where('kamar_id', $kamarId)
            ->whereIn('status', [StatusPenyewaan::Approved, StatusPenyewaan::Active])
            ->first();
    }
}
