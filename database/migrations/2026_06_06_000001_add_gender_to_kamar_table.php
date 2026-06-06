<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kamar', function (Blueprint $table) {
            $table->enum('gender', ['putra', 'putri', 'campur'])
                  ->default('campur')
                  ->after('tipe')
                  ->comment('Target gender penghuni kamar');
        });
    }

    public function down(): void
    {
        Schema::table('kamar', function (Blueprint $table) {
            $table->dropColumn('gender');
        });
    }
};
