<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fasilitas', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('slug', 100)->unique();
            $table->string('icon', 50)->nullable()->comment('Emoji atau icon class');
            $table->enum('kategori', ['utama', 'kamar_mandi', 'dapur', 'keamanan', 'lainnya'])
                  ->default('lainnya');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('kamar_fasilitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kamar_id')->constrained('kamar')->onDelete('cascade');
            $table->foreignId('fasilitas_id')->constrained('fasilitas')->onDelete('cascade');

            $table->unique(['kamar_id', 'fasilitas_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kamar_fasilitas');
        Schema::dropIfExists('fasilitas');
    }
};
