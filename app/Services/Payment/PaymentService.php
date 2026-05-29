<?php

namespace App\Services\Payment;

use App\Contracts\Services\PaymentServiceInterface;
use App\Contracts\Repositories\PembayaranRepositoryInterface;
use App\Contracts\Services\TagihanServiceInterface;
use App\Models\Tagihan;
use Illuminate\Support\Str;

class PaymentService implements PaymentServiceInterface
{
    public function __construct(
        private PembayaranRepositoryInterface $pembayaranRepository,
        private TagihanServiceInterface $tagihanService
    ) {}

    public function createInvoice(Tagihan $tagihan)
    {
        // Implementation for Xendit create invoice API
    }

    public function handleWebhook(array $payload)
    {
        // Implementation for Xendit webhook callback
    }
}
