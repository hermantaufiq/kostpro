<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->string('judul', 200);
            $table->text('pesan');
            $table->enum('tipe', [
                'tagihan_baru', 'tagihan_reminder', 'tagihan_overdue',
                'pembayaran_success', 'penyewaan_approved', 'penyewaan_rejected',
                'penyewaan_expired', 'sistem',
            ])->default('sistem');

            $table->json('data')->nullable()->comment('Payload tambahan, misal tagihan_id, url, dll');
            $table->string('action_url')->nullable()->comment('URL tombol aksi');

            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index(['user_id', 'read_at']);
            $table->index('tipe');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};
