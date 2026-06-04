<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keluhan_komentars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('keluhan_id')->constrained('keluhans')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('isi');
            $table->boolean('is_admin')->default(false); // true jika komentar dari Admin
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keluhan_komentars');
    }
};
