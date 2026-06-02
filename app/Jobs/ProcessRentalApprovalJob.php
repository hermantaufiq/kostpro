<?php

namespace App\Jobs;

use App\Models\Penyewaan;
use App\Models\Tagihan;
use App\Models\Notifikasi;
use App\Enums\StatusPenyewaan;
use App\Enums\TipeNotifikasi;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessRentalApprovalJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Penyewaan $penyewaan
    ) {}

    public function handle(): void
    {
        if ($this->penyewaan->status !== StatusPenyewaan::Approved) {
            return;
        }

        // Create invoices for each month
        $startDate = $this->penyewaan->tanggal_masuk;
        $monthCount = $this->penyewaan->durasi_bulan ?? 1;

        for ($i = 0; $i < $monthCount; $i++) {
            $dueDate = $startDate->copy()->addMonths($i)->endOfMonth();
            Tagihan::create([
                'penyewaan_id' => $this->penyewaan->id,
                'nominal' => $this->penyewaan->harga_bulanan_snapshot,
                'denda' => 0,
                'tanggal_jatuh_tempo' => $dueDate,
                'status' => 'Pending',
            ]);
        }

        // Create notification for tenant
        Notifikasi::create([
            'user_id' => $this->penyewaan->user_id,
            'tipe' => TipeNotifikasi::RentalApproved,
            'judul' => 'Pengajuan Sewa Disetujui',
            'pesan' => 'Pengajuan sewa Anda untuk kamar ' . $this->penyewaan->kamar->nama . ' telah disetujui.',
            'read_at' => null,
        ]);
    }
}
