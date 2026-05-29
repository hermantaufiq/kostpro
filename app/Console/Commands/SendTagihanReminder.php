<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tagihan;
use App\Enums\StatusTagihan;

class SendTagihanReminder extends Command
{
    protected $signature = 'kostpro:send-reminder {--days=3 : Hari sebelum jatuh tempo}';
    protected $description = 'Kirim reminder tagihan yang mendekati jatuh tempo';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        $targetDate = now()->addDays($days)->toDateString();

        $upcoming = Tagihan::where('status', StatusTagihan::Unpaid)
            ->whereDate('tanggal_jatuh_tempo', $targetDate)
            ->with(['user', 'penyewaan.kamar'])
            ->get();

        $this->info("Ditemukan {$upcoming->count()} tagihan mendekati jatuh tempo (H-{$days}).");

        foreach ($upcoming as $tagihan) {
            // Dispatch mail job
            \App\Jobs\SendEmailReminderJob::dispatch($tagihan);

            $tagihan->increment('reminder_count');
            $tagihan->update(['last_reminder_at' => now()]);

            $this->info("  [SENT] Reminder untuk {$tagihan->user->email} — {$tagihan->kode_tagihan}");
        }

        $this->info("Selesai mengirim {$upcoming->count()} reminder.");
        return self::SUCCESS;
    }
}
