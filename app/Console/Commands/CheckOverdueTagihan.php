<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tagihan;
use App\Enums\StatusTagihan;

class CheckOverdueTagihan extends Command
{
    protected $signature = 'kostpro:check-overdue';
    protected $description = 'Tandai tagihan yang melewati jatuh tempo sebagai overdue dan hitung denda';

    public function handle(): int
    {
        $dendaPersen = config('app.kostpro_denda_persen', 5);

        // 1. Tandai Tagihan yang lewat jatuh tempo menjadi Overdue (Grace Period)
        $unpaidOverdue = Tagihan::where('status', StatusTagihan::Unpaid)
            ->where('tanggal_jatuh_tempo', '<', now()->startOfDay())
            ->get();

        $this->info("Ditemukan {$unpaidOverdue->count()} tagihan baru yang melewati jatuh tempo (masuk masa tenggang).");

        foreach ($unpaidOverdue as $tagihan) {
            $tagihan->update(['status' => StatusTagihan::Overdue]);
        }

        // 2. Beri Denda jika sudah Overdue dan melewati masa tenggang (3 hari)
        $overduePastGrace = Tagihan::where('status', StatusTagihan::Overdue)
            ->where('tanggal_jatuh_tempo', '<=', now()->subDays(3)->startOfDay())
            ->where('jumlah_denda', 0) // Pastikan denda belum diberikan
            ->get();

        $this->info("Ditemukan {$overduePastGrace->count()} tagihan overdue yang melewati masa tenggang 3 hari.");

        $updated = 0;
        foreach ($overduePastGrace as $tagihan) {
            $denda = (int) round(($tagihan->jumlah_tagihan * $dendaPersen) / 100);
            $tagihan->update([
                'jumlah_denda' => $denda,
                'total_tagihan' => $tagihan->jumlah_tagihan + $denda,
            ]);
            $updated++;
            $this->line("  [DENDA DIBERIKAN] Tagihan #{$tagihan->kode_tagihan} — Denda: Rp {$denda}");
        }

        $this->info("Selesai. {$updated} tagihan diberikan denda.");
        return self::SUCCESS;
    }
}
