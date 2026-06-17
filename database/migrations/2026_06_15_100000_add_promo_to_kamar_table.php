<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kamar', function (Blueprint $table) {
            $table->boolean('promo_aktif')->default(false)->after('harga_deposit');
            $table->unsignedBigInteger('harga_promo')->nullable()->after('promo_aktif');
            $table->date('promo_mulai')->nullable()->after('harga_promo');
            $table->date('promo_selesai')->nullable()->after('promo_mulai');
            $table->string('label_promo', 50)->nullable()->after('promo_selesai');
        });
    }

    public function down(): void
    {
        Schema::table('kamar', function (Blueprint $table) {
            $table->dropColumn([
                'promo_aktif',
                'harga_promo',
                'promo_mulai',
                'promo_selesai',
                'label_promo',
            ]);
        });
    }
};
