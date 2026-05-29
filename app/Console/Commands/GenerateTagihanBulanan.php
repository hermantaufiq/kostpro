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
        $targetBulan = $this->option('bulan')
            ? Carbon::createFromFormat('Y-m', $this->option('bulan'))
            : now();

        $isDryRun = $this->option('dry-run');

        $this->info("Generating tagihan untuk: {$targetBulan->format('F Y')}");
        if ($isDryRun) {
            $this->warn("DRY RUN MODE — tidak ada data yang disimpan.");
        }

        $penyewaanAktif = Penyewaan::where('status', StatusPenyewaan::Active)
            ->with(['user', 'kamar'])
            ->get();

        $this->info("Ditemukan {$penyewaanAktif->count()} penyewaan aktif.");

        $generated = 0;
        $skipped = 0;

        foreach ($penyewaanAktif as $penyewaan) {
            // Cek apakah tagihan untuk bulan ini sudah ada
            $exists = Tagihan::where('penyewaan_id', $penyewaan->id)
                ->where('periode_bulan', $targetBulan->month)
                ->where('periode_tahun', $targetBulan->year)
                ->exists();

            if ($exists) {
                $this->line("  [SKIP] Penyewaan #{$penyewaan->kode_penyewaan} — tagihan sudah ada.");
                $skipped++;
                continue;
            }

            if (!$isDryRun) {
                DB::transaction(function () use ($penyewaan, $targetBulan) {
                    $kode = 'INV-' . $targetBulan->format('Ym') . '-' . strtoupper(Str::random(6));
                    $jatuhTempo = $targetBulan->copy()->day(config('app.kostpro_tanggal_jatuh_tempo', 10));

                    Tagihan::create([
                        'penyewaan_id' => $penyewaan->id,
                        'user_id' => $penyewaan->user_id,
                        'kode_tagihan' => $kode,
                        'periode_bulan' => $targetBulan->month,
                        'periode_tahun' => $targetBulan->year,
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

            $this->info("  [OK] Penyewaan #{$penyewaan->kode_penyewaan} — tagihan dibuat.");
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
