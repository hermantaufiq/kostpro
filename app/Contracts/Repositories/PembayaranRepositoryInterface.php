<?php

namespace App\Contracts\Repositories;

interface PembayaranRepositoryInterface extends BaseRepositoryInterface
{
    public function findByXenditId($xenditInvoiceId);
}
