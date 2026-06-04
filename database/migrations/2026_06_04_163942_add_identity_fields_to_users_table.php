<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambah kolom baru jika belum ada
            if (!Schema::hasColumn('users', 'tanggal_lahir')) {
                $table->date('tanggal_lahir')->nullable()->after('alamat');
            }
            if (!Schema::hasColumn('users', 'jenis_kelamin')) {
                $table->enum('jenis_kelamin', ['L', 'P'])->nullable()->after('tanggal_lahir');
            }
            if (!Schema::hasColumn('users', 'pekerjaan')) {
                $table->string('pekerjaan')->nullable()->after('jenis_kelamin');
            }
            if (!Schema::hasColumn('users', 'asal_kota')) {
                $table->string('asal_kota')->nullable()->after('pekerjaan');
            }
            if (!Schema::hasColumn('users', 'kontak_darurat_nama')) {
                $table->string('kontak_darurat_nama')->nullable()->after('asal_kota');
            }
            if (!Schema::hasColumn('users', 'kontak_darurat_hp')) {
                $table->string('kontak_darurat_hp', 20)->nullable()->after('kontak_darurat_nama');
            }
            if (!Schema::hasColumn('users', 'profil_lengkap')) {
                $table->boolean('profil_lengkap')->default(false)->after('kontak_darurat_hp');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'tanggal_lahir', 'jenis_kelamin', 'pekerjaan',
                'asal_kota', 'kontak_darurat_nama', 'kontak_darurat_hp', 'profil_lengkap'
            ]);
        });
    }
};
