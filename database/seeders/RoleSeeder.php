<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Setup Roles
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $adminOperasional = Role::firstOrCreate(['name' => 'admin_operasional']);
        $adminKeuangan = Role::firstOrCreate(['name' => 'admin_keuangan']);

        // Create Super Admin User
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@kostpro.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'phone' => '080000000000',
                'user_type' => 'admin',
                'is_active' => true,
            ]
        );

        $adminUser->assignRole($superAdmin);
    }
}

