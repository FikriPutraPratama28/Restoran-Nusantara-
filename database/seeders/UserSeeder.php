<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ── Super Admin ───────────────────────────────────────────────────
        User::firstOrCreate(
            ['email' => 'superadmin@warung.id'],
            [
                'name'      => 'Super Administrator',
                'password'  => Hash::make('superadmin123'),
                'role'      => 'super_admin',
                'is_active' => true,
            ]
        );

        // ── Admin ─────────────────────────────────────────────────────────
        User::firstOrCreate(
            ['email' => 'admin@warung.id'],
            [
                'name'      => 'Resto Admin',
                'password'  => Hash::make('admin123'),
                'role'      => 'admin',
                'is_active' => true,
            ]
        );

        // ── Pelanggan Demo ────────────────────────────────────────────────
        User::firstOrCreate(
            ['email' => 'pelanggan@warung.id'],
            [
                'name'      => 'Rina Marlina',
                'password'  => Hash::make('pelanggan123'),
                'role'      => 'pelanggan',
                'phone'     => '084567890123',
                'is_active' => true,
            ]
        );

        $this->command->info('✅ Users seeded!');
        $this->command->info('   Super Admin → superadmin@warung.id / superadmin123');
        $this->command->info('   Admin       → admin@warung.id / admin123');
        $this->command->info('   Pelanggan   → pelanggan@warung.id / pelanggan123');
    }
}
