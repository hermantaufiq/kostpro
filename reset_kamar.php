<?php
// Script untuk reset status kamar untuk testing
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$app->boot();

use Illuminate\Support\Facades\DB;

// Tampilkan semua kamar dan statusnya
$kamars = DB::table('kamar')->get(['id', 'nama', 'status']);
echo "=== STATUS KAMAR SAAT INI ===\n";
foreach ($kamars as $k) {
    echo "ID:{$k->id} | {$k->nama} => {$k->status}\n";
}

// Reset kamar yang berstatus 'reserved' menjadi 'tersedia'
$updated = DB::table('kamar')
    ->where('status', 'reserved')
    ->update(['status' => 'tersedia']);

echo "\n=== RESET ===\n";
echo "{$updated} kamar berhasil direset dari 'reserved' ke 'tersedia'.\n";

// Juga cek penyewaan pending yang mungkin double
$penyewaan = DB::table('penyewaan')->get(['id', 'kamar_id', 'status', 'user_id']);
echo "\n=== PENYEWAAN AKTIF ===\n";
foreach ($penyewaan as $p) {
    echo "ID:{$p->id} | Kamar:{$p->kamar_id} | User:{$p->user_id} | Status:{$p->status}\n";
}
