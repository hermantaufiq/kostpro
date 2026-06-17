<?php

namespace App\Contracts\Services;

use App\Models\Tagihan;
use App\Models\Voucher;

interface PaymentServiceInterface
{
    public function createInvoice(Tagihan $tagihan, ?Voucher $voucher = null): array;
    public function handleWebhook(array $payload): void;
}
