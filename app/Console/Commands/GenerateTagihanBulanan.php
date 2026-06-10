<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Penyewaan;
use App\Models\Tagihan;
use App\Enums\StatusPenyewaan;
use App\Enums\StatusTagihan;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class GenerateTagihanBulanan extends Command
{
    protected $signature = 'kostpro:generate-tagihan {--bulan= : Bulan target (format: Y-m)} {--dry-run : Preview saja, tidak simpan ke DB}';
    protected $description = 'Generate tagihan bulanan otomatis untuk semua penyewaan aktif';

    public function handle(): int
    {
        $isDryRun = $this->option('dry-run');

        $this->info("Menjalankan pengecekan auto-renewal tagihan...");
        if ($isDryRun) {
            $this->warn("DRY RUN MODE — tidak ada data yang disimpan.");
        }

        $penyewaanAktif = Penyewaan::where('status', StatusPenyewaan::Active)
            ->where('is_auto_renewal', true)
            ->whereNotNull('tanggal_keluar')
            ->whereDate('tanggal_keluar', '<=', now()->addDays(7))
            ->with(['user', 'kamar'])
            ->get();

        $this->info("Ditemukan {$penyewaanAktif->count()} penyewaan yang mendekati jatuh tempo (H-7).");

        $generated = 0;
        $skipped = 0;

        foreach ($penyewaanAktif as $penyewaan) {
            // Periode tagihan berikutnya (misal sewa 1 bulan, maka tagihan untuk bulan depannya)
            $durasi = $penyewaan->durasi_bulan ?: 1;
            $nextPeriod = Carbon::parse($penyewaan->tanggal_keluar)->addMonths($durasi);

            // Cek apakah tagihan untuk periode berikutnya sudah ada
            $exists = Tagihan::where('penyewaan_id', $penyewaan->id)
                ->where('periode_bulan', $nextPeriod->month)
                ->where('periode_tahun', $nextPeriod->year)
                ->exists();

            if ($exists) {
                $this->line("  [SKIP] Penyewaan #{$penyewaan->kode_penyewaan} — tagihan periode {$nextPeriod->format('m/Y')} sudah ada.");
                $skipped++;
                continue;
            }

            if (!$isDryRun) {
                DB::transaction(function () use ($penyewaan, $nextPeriod) {
                    $kode = 'INV-' . $nextPeriod->format('Ym') . '-' . strtoupper(Str::random(6));
                    // Jatuh tempo diset sama dengan tanggal_keluar bulan ini
                    $jatuhTempo = Carbon::parse($penyewaan->tanggal_keluar);

                    Tagihan::create([
                        'penyewaan_id' => $penyewaan->id,
                        'user_id' => $penyewaan->user_id,
                        'kode_tagihan' => $kode,
                        'periode_bulan' => $nextPeriod->month,
                        'periode_tahun' => $nextPeriod->year,
                        'jumlah_tagihan' => $penyewaan->harga_bulanan_snapshot,
                        'jumlah_denda' => 0,
                        'total_tagihan' => $penyewaan->harga_bulanan_snapshot,
                        'status' => StatusTagihan::Unpaid,
                        'tanggal_tagihan' => now(),
                        'tanggal_jatuh_tempo' => $jatuhTempo,
                        'is_auto_generated' => true,
                    ]);
                });
            }

            $this->info("  [OK] Penyewaan #{$penyewaan->kode_penyewaan} — tagihan periode {$nextPeriod->format('m/Y')} dibuat.");
            $generated++;
        }

        $this->newLine();
        $this->table(
            ['Total Aktif', 'Dibuat', 'Dilewati'],
            [[$penyewaanAktif->count(), $generated, $skipped]]
        );

        return self::SUCCESS;
    }
}
