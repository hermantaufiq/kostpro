<?php

namespace App\Jobs;

use App\Models\Penyewaan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\PenyewaanApprovedMail;
use App\Mail\PenyewaanRejectedMail;
use Illuminate\Support\Facades\Log;

class SendEmailApprovalJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(
        public Penyewaan $penyewaan,
        public string $type = 'approved' // 'approved' | 'rejected'
    ) {}

    public function handle(): void
    {
        try {
            $mail = $this->type === 'approved'
                ? new PenyewaanApprovedMail($this->penyewaan)
                : new PenyewaanRejectedMail($this->penyewaan);

            Mail::to($this->penyewaan->user->email)->send($mail);

            Log::info("Approval email ({$this->type}) sent to {$this->penyewaan->user->email}");
        } catch (\Exception $e) {
            Log::error("Failed to send approval email", ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}
