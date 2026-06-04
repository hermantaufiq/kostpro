<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('request_layanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('layanan_tambahan_id')->constrained('layanan_tambahans')->cascadeOnDelete();
            $table->foreignId('penyewaan_id')->constrained('penyewaan')->cascadeOnDelete();
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->text('catatan_user')->nullable();
            $table->text('catatan_admin')->nullable();
            $table->foreignId('tagihan_item_id')->nullable()->constrained('tagihan_items')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_layanans');
    }
};
