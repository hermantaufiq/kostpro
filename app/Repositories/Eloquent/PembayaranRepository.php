<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\PembayaranRepositoryInterface;
use App\Models\Pembayaran;

class PembayaranRepository extends BaseRepository implements PembayaranRepositoryInterface
{
    public function __construct(Pembayaran $model)
    {
        parent::__construct($model);
    }

    public function findByXenditId($xenditInvoiceId)
    {
        return $this->model->where('xendit_invoice_id', $xenditInvoiceId)->first();
    }
}
