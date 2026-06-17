<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\LandingController;
use App\Http\Controllers\Public\KamarController;

Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/kamar', [KamarController::class, 'index'])->name('kamar.index');
Route::get('/kamar/{id}', [KamarController::class, 'show'])->name('kamar.show');
Route::post('/kamar/{id}/waiting-list', [KamarController::class, 'storeWaitingList'])->name('kamar.waiting_list.store');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\User\DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/notifikasi/read-all', [\App\Http\Controllers\User\DashboardController::class, 'readAllNotifikasi'])->name('user.notifikasi.readAll');
    Route::get('/panduan', function() { return view('user.panduan'); })->name('user.panduan');

    // Penyewaan
    Route::get('/kamar/{id}/sewa', [\App\Http\Controllers\User\PenyewaanController::class, 'create'])->name('user.penyewaan.create');
    Route::post('/kamar/{id}/sewa', [\App\Http\Controllers\User\PenyewaanController::class, 'store'])->name('user.penyewaan.store');
    Route::get('/penyewaan', [\App\Http\Controllers\User\PenyewaanController::class, 'index'])->name('user.penyewaan.index');
    Route::get('/penyewaan/{id}', [\App\Http\Controllers\User\PenyewaanController::class, 'show'])->name('user.penyewaan.show');
    Route::post('/penyewaan/{id}/sign-contract', [\App\Http\Controllers\User\PenyewaanController::class, 'signContract'])->name('user.penyewaan.sign_contract');
    Route::post('/penyewaan/{id}/self-service', [\App\Http\Controllers\User\PenyewaanController::class, 'selfService'])->name('user.penyewaan.self_service');
    Route::post('/sewa/{id}/perpanjang', [\App\Http\Controllers\User\DashboardController::class, 'perpanjangSewa'])->name('user.sewa.perpanjang');

    // Tagihan
    Route::get('/tagihan', [\App\Http\Controllers\User\TagihanController::class, 'index'])->name('user.tagihan.index');
    Route::get('/tagihan/{id}', [\App\Http\Controllers\User\TagihanController::class, 'show'])->name('user.tagihan.show');
    
    // Payment
    Route::post('/tagihan/{id}/pay', [\App\Http\Controllers\Payment\PaymentController::class, 'create'])->name('payment.create');
    Route::get('/payment/{id}/instruction', [\App\Http\Controllers\Payment\PaymentController::class, 'instruction'])->name('payment.instruction');
    Route::post('/payment/{id}/simulate', [\App\Http\Controllers\Payment\PaymentController::class, 'simulateSuccess'])->name('payment.simulate');

    // Profil
    Route::get('/profil', [\App\Http\Controllers\User\ProfilController::class, 'edit'])->name('user.profil.edit');
    Route::put('/profil', [\App\Http\Controllers\User\ProfilController::class, 'update'])->name('user.profil.update');
    Route::put('/profil/password', [\App\Http\Controllers\User\ProfilController::class, 'updatePassword'])->name('user.profil.password');

    // Keluhan
    Route::get('/layanan-tambahan', [\App\Http\Controllers\User\LayananTambahanController::class, 'index'])->name('user.layanan.index');
    Route::post('/layanan-tambahan', [\App\Http\Controllers\User\LayananTambahanController::class, 'store'])->name('user.layanan.store');

    Route::get('/keluhan', [\App\Http\Controllers\User\KeluhanController::class, 'index'])->name('user.keluhan.index');
    Route::get('/keluhan/buat', [\App\Http\Controllers\User\KeluhanController::class, 'create'])->name('user.keluhan.create');
    Route::post('/keluhan', [\App\Http\Controllers\User\KeluhanController::class, 'store'])->name('user.keluhan.store');
    Route::get('/keluhan/{keluhan}', [\App\Http\Controllers\User\KeluhanController::class, 'show'])->name('user.keluhan.show');
    Route::post('/keluhan/{keluhan}/komentar', [\App\Http\Controllers\User\KeluhanController::class, 'addKomentar'])->name('user.keluhan.komentar');

    // Pasar Kos
    Route::get('/pasar-kos', [\App\Http\Controllers\User\PasarKosController::class, 'index'])->name('user.pasar_kos.index');
    Route::post('/pasar-kos', [\App\Http\Controllers\User\PasarKosController::class, 'store'])->name('user.pasar_kos.store');
    Route::delete('/pasar-kos/{pasarKo}', [\App\Http\Controllers\User\PasarKosController::class, 'destroy'])->name('user.pasar_kos.destroy');
});

Route::get('/payment/return', [\App\Http\Controllers\Payment\PaymentController::class, 'return'])->name('payment.return');
Route::post('/webhook/xendit', [\App\Http\Controllers\Payment\WebhookController::class, 'handle'])->name('webhook.xendit');

require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {
    Route::get('/invoice/{id}/print', [\App\Http\Controllers\User\TagihanController::class, 'print'])->name('invoice.print');
    Route::get('/invoice/{id}/download', [\App\Http\Controllers\User\TagihanController::class, 'downloadPdf'])->name('invoice.download');
});
