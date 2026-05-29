<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Contracts\Services\PaymentServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function __construct(
        private PaymentServiceInterface $paymentService
    ) {}

    public function handle(Request $request)
    {
        // Verify Xendit webhook token
        $webhookToken = $request->header('X-Callback-Token');
        $expectedToken = config('services.xendit.webhook_token');

        if ($webhookToken !== $expectedToken) {
            Log::warning('Invalid Xendit webhook token received.');
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $payload = $request->all();
        Log::info('Xendit webhook received', ['event' => $payload['event'] ?? 'unknown']);

        try {
            // Dispatch to queue for async processing
            \App\Jobs\ProcessPaymentWebhookJob::dispatch($payload);
            
            return response()->json(['message' => 'OK'], 200);
        } catch (\Exception $e) {
            Log::error('Webhook dispatch failed', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }
}
