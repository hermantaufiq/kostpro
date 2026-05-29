<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tagihan_id')->constrained('tagihan')->onDelete('restrict');
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');

            $table->string('kode_pembayaran', 30)->unique()->comment('Format: PAY-YYYYMMDD-XXXXX');

            // Xendit fields
            $table->string('xendit_invoice_id')->nullable()->unique();
            $table->string('xendit_external_id')->nullable()->unique();
            $table->string('xendit_payment_url')->nullable();

            $table->enum('metode', [
                'virtual_account', 'qris', 'ewallet', 'credit_card', 'retail'
            ])->nullable();

            $table->string('channel_code', 50)->nullable()->comment('BCA, BNI, DANA, OVO, dll');

            $table->unsignedBigInteger('jumlah');
            $table->unsignedBigInteger('biaya_admin')->default(0);
            $table->unsignedBigInteger('jumlah_diterima')->nullable();

            $table->enum('status', [
                'pending', 'success', 'failed', 'expired', 'refunded'
            ])->default('pending');

            $table->json('payload_request')->nullable()->comment('Request ke Xendit');
            $table->json('payload_response')->nullable()->comment('Response dari Xendit');
            $table->json('payload_webhook')->nullable()->comment('Webhook dari Xendit');

            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();

            $table->timestamps();

            $table->index('tagihan_id');
            $table->index('user_id');
            $table->index('status');
            $table->index('xendit_invoice_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
