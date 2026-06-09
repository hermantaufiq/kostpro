<?php

namespace App\Jobs;

use App\Models\Penyewaan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\KontrakAkanHabisMail;
use Illuminate\Support\Facades\Log;

class SendKontrakReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(
        public Penyewaan $penyewaan,
        public int $daysRemaining
    ) {}

    public function handle(): void
    {
        try {
            Mail::to($this->penyewaan->user->email)
                ->send(new KontrakAkanHabisMail($this->penyewaan, $this->daysRemaining));

            Log::info("Kontrak reminder email sent to {$this->penyewaan->user->email} for penyewaan {$this->penyewaan->kode_penyewaan} ({$this->daysRemaining} days left)");
        } catch (\Exception $e) {
            Log::error("Failed to send kontrak reminder email", [
                'penyewaan_id' => $this->penyewaan->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("SendKontrakReminderJob failed permanently", [
            'penyewaan_id' => $this->penyewaan->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
