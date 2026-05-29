<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('foto_kamar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kamar_id')
                ->constrained('kamar')
                ->onDelete('cascade');

            $table->string('foto_url')->comment('Public URL dari Supabase Storage');
            $table->string('foto_path')->comment('Path relatif di storage bucket');
            $table->string('foto_thumb_url')->nullable()->comment('URL thumbnail WebP');
            $table->string('foto_thumb_path')->nullable();
            $table->string('mime_type', 50)->default('image/webp');
            $table->unsignedInteger('ukuran_file')->nullable()->comment('Bytes');
            $table->unsignedSmallInteger('lebar')->nullable()->comment('Pixel width');
            $table->unsignedSmallInteger('tinggi')->nullable()->comment('Pixel height');
            $table->boolean('is_thumbnail')->default(false)->comment('Foto utama kamar');
            $table->unsignedTinyInteger('sort_order')->default(0)->comment('Urutan tampil');
            $table->string('caption', 255)->nullable();
            $table->timestamps();

            $table->index('kamar_id');
            $table->index(['kamar_id', 'is_thumbnail']);
            $table->index(['kamar_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('foto_kamar');
    }
};
