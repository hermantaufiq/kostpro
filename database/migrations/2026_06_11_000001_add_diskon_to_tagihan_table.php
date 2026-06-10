<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tagihan', function (Blueprint $table) {
            // Jumlah bulan yang dicakup oleh tagihan ini (default 1)
            $table->unsignedTinyInteger('untuk_durasi_bulan')->default(1)->after('is_auto_generated');
            // Persen diskon yang diberikan (0-100)
            $table->unsignedTinyInteger('diskon_persen')->default(0)->after('untuk_durasi_bulan');
            // Nominal diskon dalam rupiah
            $table->unsignedBigInteger('jumlah_diskon')->default(0)->after('diskon_persen');
        });
    }

    public function down(): void
    {
        Schema::table('tagihan', function (Blueprint $table) {
            $table->dropColumn(['untuk_durasi_bulan', 'diskon_persen', 'jumlah_diskon']);
        });
    }
};
