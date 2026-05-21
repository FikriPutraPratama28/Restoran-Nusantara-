<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin ─────────────────────────────────────────────────────────
        User::firstOrCreate(
            ['email' => 'admin@warung.id'],
            [
                'name'      => 'Administrator',
                'password'  => Hash::make('admin123'),
                'role'      => 'admin',
                'is_active' => true,
            ]
        );

        // ── Karyawan Demo ─────────────────────────────────────────────────
        $karyawanData = [
            [
                'user' => [
                    'name'     => 'Budi Santoso',
                    'email'    => 'budi@warung.id',
                    'password' => Hash::make('karyawan123'),
                    'role'     => 'karyawan',
                    'phone'    => '081234567890',
                    'is_active'=> true,
                ],
                'employee' => [
                    'employee_code'     => 'EMP001',
                    'jabatan'           => 'Chef',
                    'shift'             => 'pagi',
                    'join_date'         => '2023-01-15',
                    'address'           => 'Jl. Merdeka No. 10, Jakarta',
                    'emergency_contact' => '081298765432',
                    'salary'            => 4500000,
                    'status'            => 'aktif',
                ],
            ],
            [
                'user' => [
                    'name'     => 'Siti Rahayu',
                    'email'    => 'siti@warung.id',
                    'password' => Hash::make('karyawan123'),
                    'role'     => 'karyawan',
                    'phone'    => '082345678901',
                    'is_active'=> true,
                ],
                'employee' => [
                    'employee_code'     => 'EMP002',
                    'jabatan'           => 'Kasir',
                    'shift'             => 'siang',
                    'join_date'         => '2023-03-01',
                    'address'           => 'Jl. Sudirman No. 25, Jakarta',
                    'emergency_contact' => '082387654321',
                    'salary'            => 3500000,
                    'status'            => 'aktif',
                ],
            ],
            [
                'user' => [
                    'name'     => 'Ahmad Fauzi',
                    'email'    => 'ahmad@warung.id',
                    'password' => Hash::make('karyawan123'),
                    'role'     => 'karyawan',
                    'phone'    => '083456789012',
                    'is_active'=> true,
                ],
                'employee' => [
                    'employee_code'     => 'EMP003',
                    'jabatan'           => 'Pelayan',
                    'shift'             => 'malam',
                    'join_date'         => '2023-06-10',
                    'address'           => 'Jl. Gatot Subroto No. 5, Jakarta',
                    'emergency_contact' => '083476543210',
                    'salary'            => 3000000,
                    'status'            => 'aktif',
                ],
            ],
        ];

        foreach ($karyawanData as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['user']['email']],
                $data['user']
            );

            Employee::firstOrCreate(
                ['user_id' => $user->id],
                array_merge($data['employee'], ['user_id' => $user->id])
            );
        }

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

        $this->command->info('✅ Users & Employees seeded!');
        $this->command->info('   Admin     → admin@warung.id / admin123');
        $this->command->info('   Karyawan  → budi@warung.id / karyawan123');
        $this->command->info('   Pelanggan → pelanggan@warung.id / pelanggan123');
    }
}
