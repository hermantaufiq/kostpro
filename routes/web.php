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
});

require __DIR__.'/auth.php';
