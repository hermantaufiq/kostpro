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
        Schema::table('kamar', function (Blueprint $table) {
            // true  = kamar tampil ke pengguna (default semua kamar)
            // false = kamar disembunyikan dari pengguna (digunakan saat maintenance)
            $table->boolean('show_to_public')->default(true)->after('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kamar', function (Blueprint $table) {
            $table->dropColumn('show_to_public');
        });
    }
};
