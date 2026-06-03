<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@kostpro.local'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password123'),
                'user_type' => 'admin',
                'staff_role' => 'super_admin',
                'is_active' => 1,
            ]
        );
        $admin->assignRole('super_admin');
    }
}
