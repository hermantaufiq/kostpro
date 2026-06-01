<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Hapus semua pembayaran pending duplikat, sisakan yang success
\App\Models\Pembayaran::where('status', 'pending')->delete();

// Update semua tagihan jadi paid
\App\Models\Tagihan::where('status', '!=', 'paid')->update([
    'status' => 'paid',
    'tanggal_bayar' => now(),
]);

// Update semua penyewaan jadi approved (bukan pending)
\App\Models\Penyewaan::where('status', 'pending')->update([
    'status' => 'approved',
]);

echo "Selesai! Semua data pembayaran duplikat dihapus dan status diperbarui.";
