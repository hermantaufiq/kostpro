<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('kode_voucher', 30)->unique();
            $table->unsignedBigInteger('nominal_diskon');
            $table->enum('status', ['aktif', 'digunakan', 'kadaluarsa'])->default('aktif');
            $table->string('sumber', 50)->default('pembayaran_tepat_waktu');
            $table->foreignId('pembayaran_sumber_id')->nullable()->constrained('pembayaran')->nullOnDelete();
            $table->foreignId('tagihan_id')->nullable()->constrained('tagihan')->nullOnDelete();
            $table->foreignId('pembayaran_id')->nullable()->constrained('pembayaran')->nullOnDelete();
            $table->timestamp('berlaku_sampai');
            $table->timestamp('digunakan_pada')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('berlaku_sampai');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
