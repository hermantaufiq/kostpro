<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penyewaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('kamar_id')->constrained('kamar')->onDelete('restrict');

            $table->string('kode_penyewaan', 30)->unique()->comment('Format: PSW-YYYYMM-XXXXX');
            $table->date('tanggal_masuk');
            $table->date('tanggal_keluar')->nullable();
            $table->unsignedTinyInteger('durasi_bulan')->default(1);

            $table->enum('status', [
                'pending', 'approved', 'rejected', 'active', 'completed', 'cancelled'
            ])->default('pending');

            $table->text('catatan_penyewa')->nullable();
            $table->text('catatan_admin')->nullable();

            // Approval
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('tanggal_approval')->nullable();

            // KTP & Kontrak
            $table->string('ktp_url')->nullable()->comment('URL KTP di Supabase Storage');
            $table->string('ktp_path')->nullable();
            $table->string('kontrak_url')->nullable()->comment('URL kontrak digital');

            // Financial
            $table->unsignedBigInteger('harga_bulanan_snapshot')->comment('Harga saat pengajuan');
            $table->unsignedBigInteger('deposit_amount')->default(0);
            $table->boolean('deposit_paid')->default(false);
            $table->timestamp('deposit_paid_at')->nullable();

            // Check-in / Check-out
            $table->timestamp('checkin_at')->nullable();
            $table->timestamp('checkout_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('kamar_id');
            $table->index('status');
            $table->index('tanggal_masuk');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penyewaan');
    }
};
