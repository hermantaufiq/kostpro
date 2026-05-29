<?php

namespace App\Jobs;

use App\Contracts\Services\PaymentServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessPaymentWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 5;
    public int $backoff = 30;

    public function __construct(
        public array $payload
    ) {}

    public function handle(PaymentServiceInterface $paymentService): void
    {
        try {
            $paymentService->handleWebhook($this->payload);
        } catch (\Exception $e) {
            Log::error('ProcessPaymentWebhookJob failed', [
                'payload' => $this->payload,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
