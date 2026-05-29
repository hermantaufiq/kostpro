<?php

namespace App\Contracts\Services;

use App\Models\Tagihan;

interface PaymentServiceInterface
{
    public function createInvoice(Tagihan $tagihan);
    public function handleWebhook(array $payload);
}
