<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\TagihanRepositoryInterface;
use App\Models\Tagihan;
use App\Enums\StatusTagihan;

class TagihanRepository extends BaseRepository implements TagihanRepositoryInterface
{
    public function __construct(Tagihan $model)
    {
        parent::__construct($model);
    }

    public function findByPenyewaan($penyewaanId)
    {
        return $this->model->where('penyewaan_id', $penyewaanId)->get();
    }

    public function findOverdue()
    {
        return $this->model->where('status', StatusTagihan::Overdue)->get();
    }

    public function findUnpaid()
    {
        return $this->model->where('status', StatusTagihan::Unpaid)->get();
    }
}
