<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tagihan_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tagihan_id')->constrained('tagihan')->onDelete('cascade');
            $table->string('nama_item'); // e.g., 'Sewa Kamar', 'Listrik', 'Laundry'
            $table->decimal('nominal', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tagihan_items');
    }
};
