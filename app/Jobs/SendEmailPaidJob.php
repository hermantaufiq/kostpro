<?php

namespace App\Jobs;

use App\Models\Tagihan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentSuccessMail;
use Illuminate\Support\Facades\Log;

class SendEmailPaidJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 30;

    public function __construct(
        public Tagihan $tagihan
    ) {}

    public function handle(): void
    {
        try {
            Mail::to($this->tagihan->user->email)
                ->send(new PaymentSuccessMail($this->tagihan));

            Log::info("Payment success email sent to {$this->tagihan->user->email}");
        } catch (\Exception $e) {
            Log::error("Failed to send payment success email", ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}
