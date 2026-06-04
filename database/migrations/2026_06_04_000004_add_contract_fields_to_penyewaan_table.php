<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penyewaan', function (Blueprint $table) {
            $table->boolean('is_contract_signed')->default(false)->after('status');
            $table->timestamp('signed_at')->nullable()->after('is_contract_signed');
        });
    }

    public function down(): void
    {
        Schema::table('penyewaan', function (Blueprint $table) {
            $table->dropColumn(['is_contract_signed', 'signed_at']);
        });
    }
};
