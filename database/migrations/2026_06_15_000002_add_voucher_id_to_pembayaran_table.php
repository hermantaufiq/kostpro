<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->foreignId('voucher_id')->nullable()->after('tagihan_id')->constrained('vouchers')->nullOnDelete();
            $table->unsignedBigInteger('diskon_voucher')->default(0)->after('jumlah');
        });
    }

    public function down(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropForeign(['voucher_id']);
            $table->dropColumn(['voucher_id', 'diskon_voucher']);
        });
    }
};
