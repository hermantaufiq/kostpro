<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tagihan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penyewaan_id')->constrained('penyewaan')->onDelete('restrict');
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');

            $table->string('kode_tagihan', 30)->unique()->comment('Format: TGH-YYYYMM-XXXXX');
            $table->unsignedTinyInteger('periode_bulan')->comment('1-12');
            $table->unsignedSmallInteger('periode_tahun');

            $table->unsignedBigInteger('jumlah_tagihan')->comment('Tagihan pokok');
            $table->unsignedBigInteger('jumlah_denda')->default(0)->comment('Denda keterlambatan');
            $table->unsignedBigInteger('total_tagihan')
                  ->storedAs('jumlah_tagihan + jumlah_denda')
                  ->comment('Auto-calculated');

            $table->enum('status', ['unpaid', 'paid', 'overdue', 'cancelled'])->default('unpaid');

            $table->date('tanggal_tagihan')->comment('Tanggal generate tagihan');
            $table->date('tanggal_jatuh_tempo');
            $table->timestamp('tanggal_bayar')->nullable();

            $table->unsignedTinyInteger('reminder_count')->default(0);
            $table->timestamp('last_reminder_at')->nullable();

            $table->text('catatan')->nullable();
            $table->boolean('is_auto_generated')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('penyewaan_id');
            $table->index('status');
            $table->index(['periode_tahun', 'periode_bulan']);
            $table->index('tanggal_jatuh_tempo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tagihan');
    }
};
