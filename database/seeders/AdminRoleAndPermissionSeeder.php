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

        // Feature models that have standard CRUD permissions
        $features = [
            'kamar',
            'penyewa',
            'penyewaan',
            'tagihan',
            'pembayaran',
            'voucher',
            'notifikasi',
            'laporan',
            'pengeluaran',
            'keluhan',
            'inventaris',
        ];

        $actions = ['view_any', 'view', 'create', 'update', 'delete', 'restore', 'force_delete'];

        $allPermissions = [];

        foreach ($features as $feature) {
            foreach ($actions as $action) {
                $permissionName = $action . '_' . $feature;
                Permission::firstOrCreate(['name' => $permissionName]);
                $allPermissions[] = $permissionName;
            }
        }

        // Additional Specific Permissions
        $customPermissions = [
            'view_any_settings', // for settings page
            'export_kamar',
            'export_penyewa',
            'approve_penyewaan',
            'reject_penyewaan',
            'generate_tagihan',
            'send_reminder_tagihan',
            'export_tagihan',
            'mark_pembayaran',
            'refund_pembayaran',
            'export_pembayaran',
            'send_notifikasi',
            'broadcast_notifikasi',
            'export_laporan_pdf',
            'export_laporan_excel',
            'view_audit_log',
            'view_dashboard',
            'manage_admin_users',
            'manage_roles',
            'manage_permissions',
            'system_settings',
        ];

        foreach ($customPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
            $allPermissions[] = $permission;
        }

        // Create Roles
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $adminOp = Role::firstOrCreate(['name' => 'admin_operasional']);
        $adminKeu = Role::firstOrCreate(['name' => 'admin_keuangan']);

        // Super Admin - All Permissions
        $superAdmin->syncPermissions($allPermissions);

        // Admin Operasional Permissions
        $adminOpPermissions = [
            'view_any_kamar', 'view_kamar', 'create_kamar', 'update_kamar', 'export_kamar',
            'view_any_penyewa', 'view_penyewa', 'create_penyewa', 'update_penyewa', 'export_penyewa',
            'view_any_penyewaan', 'view_penyewaan', 'create_penyewaan', 'approve_penyewaan', 'reject_penyewaan', 'update_penyewaan',
            'view_any_notifikasi', 'view_notifikasi', 'send_notifikasi', 'broadcast_notifikasi',
            'view_any_keluhan', 'view_keluhan', 'update_keluhan',
            'view_any_inventaris', 'view_inventaris', 'create_inventaris', 'update_inventaris', 'delete_inventaris',
            'view_any_settings',
            'view_audit_log',
            'view_dashboard',
        ];
        $adminOp->syncPermissions($adminOpPermissions);

        // Admin Keuangan Permissions
        $adminKeuPermissions = [
            'view_any_tagihan', 'view_tagihan', 'create_tagihan', 'update_tagihan', 'generate_tagihan', 'send_reminder_tagihan', 'export_tagihan',
            'view_any_pembayaran', 'view_pembayaran', 'mark_pembayaran', 'refund_pembayaran', 'export_pembayaran',
            'view_any_voucher', 'view_voucher', 'create_voucher', 'update_voucher',
            'view_any_pengeluaran', 'view_pengeluaran', 'create_pengeluaran', 'update_pengeluaran',
            'view_any_laporan', 'view_laporan', 'export_laporan_pdf', 'export_laporan_excel',
            'view_any_penyewa', 'view_penyewa', 'export_penyewa',
            'view_audit_log',
            'view_dashboard',
        ];
        $adminKeu->syncPermissions($adminKeuPermissions);
    }
}
