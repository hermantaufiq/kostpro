<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminRoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // Create Permissions
        $permissions = [
            // Kamar (Room) Permissions
            'view_kamar',
            'create_kamar',
            'update_kamar',
            'delete_kamar',
            'export_kamar',

            // Penyewa (Tenant) Permissions
            'view_penyewa',
            'create_penyewa',
            'update_penyewa',
            'delete_penyewa',
            'export_penyewa',

            // Penyewaan (Rental) Permissions
            'view_penyewaan',
            'create_penyewaan',
            'approve_penyewaan',
            'reject_penyewaan',
            'update_penyewaan',
            'delete_penyewaan',

            // Tagihan (Invoice) Permissions
            'view_tagihan',
            'create_tagihan',
            'update_tagihan',
            'delete_tagihan',
            'generate_tagihan',
            'send_reminder_tagihan',
            'export_tagihan',

            // Pembayaran (Payment) Permissions
            'view_pembayaran',
            'mark_pembayaran',
            'refund_pembayaran',
            'export_pembayaran',

            // Notifikasi Permissions
            'view_notifikasi',
            'send_notifikasi',
            'broadcast_notifikasi',

            // Laporan (Report) Permissions
            'view_laporan',
            'export_laporan_pdf',
            'export_laporan_excel',

            // System Permissions
            'view_audit_log',
            'view_dashboard',
            'manage_admin_users',
            'manage_roles',
            'manage_permissions',
            'system_settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create Roles
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $adminOp = Role::firstOrCreate(['name' => 'admin_operasional']);
        $adminKeu = Role::firstOrCreate(['name' => 'admin_keuangan']);

        // Super Admin - All Permissions
        $superAdmin->syncPermissions($permissions);

        // Admin Operasional Permissions
        $adminOpPermissions = [
            'view_kamar', 'create_kamar', 'update_kamar', 'export_kamar',
            'view_penyewa', 'create_penyewa', 'update_penyewa', 'export_penyewa',
            'view_penyewaan', 'create_penyewaan', 'approve_penyewaan', 'reject_penyewaan', 'update_penyewaan',
            'view_notifikasi', 'send_notifikasi', 'broadcast_notifikasi',
            'view_audit_log',
            'view_dashboard',
        ];
        $adminOp->syncPermissions($adminOpPermissions);

        // Admin Keuangan Permissions
        $adminKeuPermissions = [
            'view_tagihan', 'create_tagihan', 'update_tagihan', 'generate_tagihan', 'send_reminder_tagihan', 'export_tagihan',
            'view_pembayaran', 'mark_pembayaran', 'refund_pembayaran', 'export_pembayaran',
            'view_laporan', 'export_laporan_pdf', 'export_laporan_excel',
            'view_penyewa', 'export_penyewa',
            'view_audit_log',
            'view_dashboard',
        ];
        $adminKeu->syncPermissions($adminKeuPermissions);
    }
}
