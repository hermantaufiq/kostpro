<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\LandingController;
use App\Http\Controllers\Public\KamarController;

Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/kamar', [KamarController::class, 'index'])->name('kamar.index');
Route::get('/kamar/{id}', [KamarController::class, 'show'])->name('kamar.show');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('user.dashboard');
    })->name('dashboard');

    // Penyewaan
    Route::get('/kamar/{id}/sewa', [\App\Http\Controllers\User\PenyewaanController::class, 'create'])->name('user.penyewaan.create');
    Route::post('/kamar/{id}/sewa', [\App\Http\Controllers\User\PenyewaanController::class, 'store'])->name('user.penyewaan.store');
    Route::get('/penyewaan', [\App\Http\Controllers\User\PenyewaanController::class, 'index'])->name('user.penyewaan.index');
    Route::get('/penyewaan/{id}', [\App\Http\Controllers\User\PenyewaanController::class, 'show'])->name('user.penyewaan.show');

    // Tagihan
    Route::get('/tagihan', [\App\Http\Controllers\User\TagihanController::class, 'index'])->name('user.tagihan.index');
    Route::get('/tagihan/{id}', [\App\Http\Controllers\User\TagihanController::class, 'show'])->name('user.tagihan.show');
    
    // Payment
    Route::post('/tagihan/{id}/pay', [\App\Http\Controllers\Payment\PaymentController::class, 'create'])->name('payment.create');
    Route::get('/payment/{id}/instruction', [\App\Http\Controllers\Payment\PaymentController::class, 'instruction'])->name('payment.instruction');

    // Profil
    Route::get('/profil', [\App\Http\Controllers\User\ProfilController::class, 'edit'])->name('user.profil.edit');
    Route::put('/profil', [\App\Http\Controllers\User\ProfilController::class, 'update'])->name('user.profil.update');
    Route::put('/profil/password', [\App\Http\Controllers\User\ProfilController::class, 'updatePassword'])->name('user.profil.password');
});

Route::get('/payment/return', [\App\Http\Controllers\Payment\PaymentController::class, 'return'])->name('payment.return');
Route::post('/webhook/xendit', [\App\Http\Controllers\Payment\WebhookController::class, 'handle'])->name('webhook.xendit');

require __DIR__.'/auth.php';

Route::get('/invoice/{id}/print', function($id) { return "Fitur Cetak Invoice #$id sedang dikembangkan"; })->name('invoice.print');
