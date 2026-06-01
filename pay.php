<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$tagihan = \App\Models\Tagihan::first();
if ($tagihan && $tagihan->status->value === 'unpaid') {
    // Cari pembayaran pending
    $pembayaran = \App\Models\Pembayaran::where('tagihan_id', $tagihan->id)->latest()->first();
    
    if ($pembayaran) {
        $pembayaran->update([
            'status' => 'success',
            'paid_at' => now(),
            'jumlah_diterima' => $tagihan->total_tagihan,
        ]);
        
        $tagihan->update([
            'status' => 'paid',
            'tanggal_bayar' => now(),
        ]);
        
        $penyewaan = \App\Models\Penyewaan::find($tagihan->penyewaan_id);
        if ($penyewaan) {
            $penyewaan->update(['status' => 'active', 'checkin_at' => now()]);
            \App\Models\Kamar::where('id', $penyewaan->kamar_id)->update(['status' => 'terisi']);
        }
        
        echo "Berhasil! Tagihan telah ditandai sebagai Lunas (Paid) dan Penyewaan menjadi Active.";
    } else {
        echo "Belum ada percobaan pembayaran (klik tombol lanjutkan pembayaran) untuk tagihan ini.";
    }
} else {
    echo "Tagihan tidak ditemukan atau sudah dibayar.";
}
