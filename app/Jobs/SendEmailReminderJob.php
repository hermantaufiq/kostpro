<?php

namespace App\Jobs;

use App\Models\Tagihan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\TagihanReminderMail;
use Illuminate\Support\Facades\Log;

class SendEmailReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(
        public Tagihan $tagihan
    ) {}

    public function handle(): void
    {
        try {
            Mail::to($this->tagihan->user->email)
                ->send(new TagihanReminderMail($this->tagihan));

            Log::info("Reminder email sent to {$this->tagihan->user->email} for tagihan {$this->tagihan->kode_tagihan}");
        } catch (\Exception $e) {
            Log::error("Failed to send reminder email", [
                'tagihan_id' => $this->tagihan->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("SendEmailReminderJob failed permanently", [
            'tagihan_id' => $this->tagihan->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
