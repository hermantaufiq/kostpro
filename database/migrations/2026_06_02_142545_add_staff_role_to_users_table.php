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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('staff_role', ['super_admin', 'admin_operasional', 'admin_keuangan'])
                  ->nullable()
                  ->after('user_type')
                  ->comment('Role untuk admin staff');

            $table->enum('assigned_department', ['operasional', 'keuangan', 'support'])
                  ->nullable()
                  ->after('staff_role')
                  ->comment('Department assignment');

            $table->timestamp('last_login_at')
                  ->nullable()
                  ->after('assigned_department')
                  ->comment('Last admin login timestamp');

            $table->unsignedInteger('login_count')
                  ->default(0)
                  ->after('last_login_at')
                  ->comment('Total login count');

            $table->index('staff_role');
            $table->index('assigned_department');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['staff_role']);
            $table->dropIndex(['assigned_department']);
            $table->dropColumn(['staff_role', 'assigned_department', 'last_login_at', 'login_count']);
        });
    }
};
