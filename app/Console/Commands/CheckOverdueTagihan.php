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

        $unpaidOverdue = Tagihan::where('status', StatusTagihan::Unpaid)
            ->where('tanggal_jatuh_tempo', '<', now()->startOfDay())
            ->get();

        $this->info("Ditemukan {$unpaidOverdue->count()} tagihan overdue.");

        $updated = 0;
        foreach ($unpaidOverdue as $tagihan) {
            $denda = (int) round(($tagihan->jumlah_tagihan * $dendaPersen) / 100);
            $tagihan->update([
                'status' => StatusTagihan::Overdue,
                'jumlah_denda' => $denda,
                'total_tagihan' => $tagihan->jumlah_tagihan + $denda,
            ]);
            $updated++;
            $this->line("  [OVERDUE] Tagihan #{$tagihan->kode_tagihan} — Denda: Rp {$denda}");
        }

        $this->info("Selesai. {$updated} tagihan ditandai overdue.");
        return self::SUCCESS;
    }
}
