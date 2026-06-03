<?php

namespace App\Jobs;

use App\Models\Pembayaran;
use App\Models\Notifikasi;
use App\Enums\TipeNotifikasi;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessPaymentConfirmationJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Pembayaran $pembayaran
    ) {}

    public function handle(): void
    {
        if ($this->pembayaran->status->value !== 'Lunas') {
            return;
        }

        // Update tagihan status
        $this->pembayaran->tagihan->update(['status' => 'Lunas']);

        // Create notification for tenant
        Notifikasi::create([
            'user_id' => $this->pembayaran->tagihan->penyewaan->user_id,
            'tipe' => TipeNotifikasi::PembayaranSucces,
            'judul' => 'Pembayaran Dikonfirmasi',
            'pesan' => 'Pembayaran Anda sebesar Rp ' . number_format($this->pembayaran->jumlah_diterima, 0, ',', '.') . ' telah dikonfirmasi.',
            'read_at' => null,
        ]);
    }
}
