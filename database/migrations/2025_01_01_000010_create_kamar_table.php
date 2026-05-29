<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kamar', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kamar', 20)->unique();
            $table->string('nama', 100);
            $table->enum('tipe', ['standar', 'deluxe', 'vip', 'suite'])->default('standar');
            $table->unsignedTinyInteger('lantai')->default(1);
            $table->string('luas', 20)->nullable()->comment('Contoh: 4x5');
            $table->unsignedBigInteger('harga_bulanan');
            $table->unsignedBigInteger('harga_deposit')->default(0);
            $table->text('deskripsi')->nullable();
            $table->json('fasilitas')->nullable()->comment('Array fasilitas tambahan');
            $table->enum('status', ['tersedia', 'terisi', 'maintenance', 'reserved'])->default('tersedia');
            $table->json('meta')->nullable()->comment('Data tambahan: luas_m2, orientasi, dll');
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('tipe');
            $table->index('lantai');
            $table->index('harga_bulanan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kamar');
    }
};
