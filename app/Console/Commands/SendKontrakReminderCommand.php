<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Penyewaan;
use App\Enums\StatusPenyewaan;

class SendKontrakReminderCommand extends Command
{
    protected $signature = 'kostpro:send-kontrak-reminder';
    protected $description = 'Kirim reminder untuk kontrak yang akan habis dalam 30, 14, dan 7 hari';

    public function handle(): int
    {
        $intervals = [30, 14, 7];

        $this->info("Memeriksa kontrak yang akan habis dalam: " . implode(', ', $intervals) . " hari.");

        $totalSent = 0;

        foreach ($intervals as $days) {
            $targetDate = now()->addDays($days)->toDateString();

            $upcoming = Penyewaan::where('status', StatusPenyewaan::Active)
                ->whereDate('tanggal_keluar', $targetDate)
                ->with(['user', 'kamar'])
                ->get();

            if ($upcoming->count() > 0) {
                $this->info("H-{$days} ({$targetDate}): Ditemukan {$upcoming->count()} kontrak.");
                
                foreach ($upcoming as $penyewaan) {
                    \App\Jobs\SendKontrakReminderJob::dispatch($penyewaan, $days);
                    
                    \App\Models\Notifikasi::create([
                        'user_id' => $penyewaan->user_id,
                        'tipe' => \App\Enums\TipeNotifikasi::KontrakAkanHabis,
                        'judul' => 'Pengingat Perpanjangan Kontrak',
                        'pesan' => "Kontrak kamar {$penyewaan->kamar->nama} Anda akan berakhir dalam {$days} hari ({$targetDate}). Segera lakukan perpanjangan.",
                        'read_at' => null,
                    ]);

                    $totalSent++;
                    $this->line("  [SENT] Reminder H-{$days} ke {$penyewaan->user->email} (Kamar {$penyewaan->kamar->nama})");
                }
            }
        }

        $this->info("Selesai. Total {$totalSent} reminder dikirim.");
        return self::SUCCESS;
    }
}
