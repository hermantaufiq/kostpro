<?php

namespace App\Observers;

use App\Models\Pembayaran;
use Carbon\Carbon;

class PembayaranObserver
{
    /**
     * Handle the Pembayaran "updated" event.
     */
    public function updated(Pembayaran $pembayaran): void
    {
        if ($pembayaran->wasChanged('status') && in_array($pembayaran->status?->value ?? $pembayaran->status, ['success', 'paid'])) {

            $tagihan = $pembayaran->tagihan;
            
            if ($tagihan && $tagihan->status !== 'paid') {
                $tagihan->update([
                    'status' => 'paid',
                    'tanggal_bayar' => now(),
                ]);

                $penyewaan = $tagihan->penyewaan;
                if ($penyewaan) {
                    // Jika ini adalah pembayaran pertama (status masih approved), ubah status menjadi Active
                    $isFirstPayment = $penyewaan->status->value === 'approved';
                    $newStatus = $isFirstPayment ? 'active' : $penyewaan->status->value;

                    // Gunakan untuk_durasi_bulan dari tagihan (untuk tagihan gabungan/diskon bisa > 1)
                    // Default 1 jika tidak ada (untuk tagihan bulanan biasa)
                    $tambahanBulan = $tagihan->untuk_durasi_bulan ?? 1;

                    // Tentukan base date untuk penambahan bulan
                    $baseDate = $penyewaan->tanggal_keluar
                        ? \Carbon\Carbon::parse($penyewaan->tanggal_keluar)
                        : \Carbon\Carbon::parse($penyewaan->tanggal_masuk);

                    // Tambahkan durasi sesuai tagihan (bisa 1, 6, atau 12 bulan sekaligus)
                    $tanggalKeluarBaru = $baseDate->copy()->addMonths($tambahanBulan);

                    $penyewaan->update([
                        'status'          => $newStatus,
                        'tanggal_keluar'  => $tanggalKeluarBaru,
                        'perpanjangan_ke' => $penyewaan->perpanjangan_ke + ($isFirstPayment ? 0 : 1),
                        'checkin_at'      => $isFirstPayment ? now() : $penyewaan->checkin_at,
                        'deposit_paid'    => $isFirstPayment ? true : $penyewaan->deposit_paid,
                        'deposit_paid_at' => $isFirstPayment ? now() : $penyewaan->deposit_paid_at,
                    ]);

                    // Jika ini pembayaran pertama, pastikan kamar juga terupdate jadi Terisi
                    if ($isFirstPayment) {
                        $penyewaan->kamar->update(['status' => 'terisi']);
                    }
                }
            }
        }
    }
}
