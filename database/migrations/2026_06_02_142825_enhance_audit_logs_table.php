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
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('deleted_by')
                  ->nullable()
                  ->after('user_agent')
                  ->comment('User ID who deleted the record');

            $table->string('reason')
                  ->nullable()
                  ->after('deleted_by')
                  ->comment('Reason for deletion');

            $table->string('status_before', 50)
                  ->nullable()
                  ->after('reason')
                  ->comment('Status before change');

            $table->string('status_after', 50)
                  ->nullable()
                  ->after('status_before')
                  ->comment('Status after change');

            $table->string('ip_address_v2', 45)
                  ->nullable()
                  ->after('status_after')
                  ->comment('IPv4 or IPv6 address');

            $table->index('deleted_by');
            $table->index('status_before');
            $table->index('status_after');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropIndex(['deleted_by']);
            $table->dropIndex(['status_before']);
            $table->dropIndex(['status_after']);
            $table->dropColumn(['deleted_by', 'reason', 'status_before', 'status_after', 'ip_address_v2']);
        });
    }
};
