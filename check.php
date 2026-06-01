<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Cek semua pembayaran
$pembayarans = \App\Models\Pembayaran::all();
foreach ($pembayarans as $p) {
    echo "Pembayaran ID: {$p->id}, Kode: {$p->kode_pembayaran}, Status: {$p->status->value}\n";
}

// Cek semua tagihan
$tagihans = \App\Models\Tagihan::all();
foreach ($tagihans as $t) {
    echo "Tagihan ID: {$t->id}, Kode: {$t->kode_tagihan}, Status: {$t->status->value}\n";
}

// Cek semua penyewaan
$penyewaans = \App\Models\Penyewaan::all();
foreach ($penyewaans as $p) {
    echo "Penyewaan ID: {$p->id}, Status: {$p->status->value}\n";
}
