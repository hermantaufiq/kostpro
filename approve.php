<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$penyewaan = \App\Models\Penyewaan::first();
if ($penyewaan) {
    $penyewaan->update(['status' => 'approved']);
    
    \App\Models\Tagihan::create([
        'penyewaan_id' => $penyewaan->id,
        'user_id' => $penyewaan->user_id,
        'kode_tagihan' => 'INV-' . time(),
        'periode_bulan' => now()->month,
        'periode_tahun' => now()->year,
        'tanggal_tagihan' => now()->toDateString(),
        'jumlah_tagihan' => $penyewaan->harga_bulanan_snapshot,
        'jumlah_denda' => 0,
        'status' => 'unpaid',
        'tanggal_jatuh_tempo' => now()->addDays(3),
    ]);
    echo "Berhasil! Penyewaan telah di-approve dan Tagihan pertama telah dibuat.";
} else {
    echo "Penyewaan tidak ditemukan atau sudah tidak pending.";
}
