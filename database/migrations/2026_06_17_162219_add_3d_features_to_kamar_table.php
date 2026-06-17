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
            $table->string('foto_360')->nullable()->after('images');
            $table->string('warna_dinding')->nullable()->default('#e8f4f8')->after('foto_360');
            $table->string('warna_lantai')->nullable()->default('#8b5a2b')->after('warna_dinding');
            $table->string('warna_kasur')->nullable()->default('#ffffff')->after('warna_lantai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kamar', function (Blueprint $table) {
            $table->dropColumn(['foto_360', 'warna_dinding', 'warna_lantai', 'warna_kasur']);
        });
    }
};
