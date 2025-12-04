<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Division;
use App\Models\Holiday;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Database Seeder - Mengisi data awal untuk sistem
 * Membuat user demo, divisi, dan hari libur contoh
 * 
 * @package Database\Seeders
 * @author Sistem Manajemen Cuti
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Menjalankan seeding database
     * Membuat data demo untuk testing sistem
     * 
     * Password default semua akun: "password"
     * 
     * @return void
     */
    public function run(): void
    {
        $password = Hash::make('password');

        // Membuat user dengan role Admin
        User::create([
            'name' => 'Admin Sistem',
            'username' => 'admin',
            'email' => 'admin@kantor.com',
            'password' => $password,
            'role' => 'admin',
            'annual_leave_quota' => 12,
        ]);

        // Membuat user dengan role HRD (Human Resource Development)
        User::create([
            'name' => 'Ibu HRD',
            'username' => 'hrd',
            'email' => 'hrd@kantor.com',
            'password' => $password,
            'role' => 'hr',
            'annual_leave_quota' => 12,
        ]);

        // Membuat user dengan role Manager Divisi (IT Manager)
        $managerIT = User::create([
            'name' => 'Pak Budi (Manager IT)',
            'username' => 'manager_it',
            'email' => 'budi@kantor.com',
            'password' => $password,
            'role' => 'division_manager',
            'annual_leave_quota' => 12,
        ]);


        // Membuat divisi IT dan menetapkan manager
        $divisiIT = Division::create([
            'name' => 'Information Technology',
            'description' => 'Tim Pengembang Aplikasi',
            'manager_id' => $managerIT->id,
        ]);

        // Mengupdate divisi manager agar terdaftar sebagai anggota divisi
        $managerIT->update(['division_id' => $divisiIT->id]);

        // Membuat karyawan biasa (staff IT) - bawahan manager
        User::create([
            'name' => 'Andi Staff',
            'username' => 'andi',
            'email' => 'andi@kantor.com',
            'password' => $password,
            'role' => 'employee',
            'division_id' => $divisiIT->id,
            'annual_leave_quota' => 12,
            'join_date' => '2023-01-01',
        ]);

        // Membuat karyawan baru (belum memiliki kuota cuti tahunan)
        User::create([
            'name' => 'Siti Junior',
            'username' => 'siti',
            'email' => 'siti@kantor.com',
            'password' => $password,
            'role' => 'employee',
            'division_id' => $divisiIT->id,
            'annual_leave_quota' => 0,
            'join_date' => date('Y-m-d'),
        ]);

        // Membuat data hari libur nasional/cuti bersama
        Holiday::create([
            'holiday_date' => '2025-12-25',
            'description' => 'Hari Raya Natal',
        ]);
        
        Holiday::create([
            'holiday_date' => '2025-01-01',
            'description' => 'Tahun Baru Masehi',
        ]);
    }
}