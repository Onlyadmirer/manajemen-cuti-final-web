<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Division;
use App\Models\Holiday;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Password default untuk semua akun: "password"
        $password = Hash::make('password');

        // 1. Buat ADMIN
        User::create([
            'name' => 'Admin Sistem',
            'username' => 'admin',
            'email' => 'admin@kantor.com',
            'password' => $password,
            'role' => 'admin',
            'annual_leave_quota' => 12,
        ]);

        // 2. Buat HRD
        User::create([
            'name' => 'Ibu HRD',
            'username' => 'hrd',
            'email' => 'hrd@kantor.com',
            'password' => $password,
            'role' => 'hr',
            'annual_leave_quota' => 12,
        ]);

        // 3. Buat KETUA DIVISI (Manager IT)
        // Kita buat usernya dulu
        $managerIT = User::create([
            'name' => 'Pak Budi (Manager IT)',
            'username' => 'manager_it',
            'email' => 'budi@kantor.com',
            'password' => $password,
            'role' => 'division_manager',
            'annual_leave_quota' => 12,
        ]);


        // 4. Buat DIVISI IT dan pasang Manager tadi sebagai ketuanya
        $divisiIT = Division::create([
            'name' => 'Information Technology',
            'description' => 'Tim Pengembang Aplikasi',
            'manager_id' => $managerIT->id,
        ]);

        // Update data Pak Budi agar dia juga terdata masuk di divisi IT
        $managerIT->update(['division_id' => $divisiIT->id]);

        // 5. Buat KARYAWAN BIASA (Staff IT)
        // Karyawan ini bawahan Pak Budi
        User::create([
            'name' => 'Andi Staff',
            'username' => 'andi',
            'email' => 'andi@kantor.com',
            'password' => $password,
            'role' => 'employee',
            'division_id' => $divisiIT->id, // Masuk ke divisi IT
            'annual_leave_quota' => 12,
            'join_date' => '2023-01-01', // Sudah > 1 tahun
        ]);

        // 6. Buat KARYAWAN BARU (Belum 1 Tahun / Contoh User Lain)
        User::create([
            'name' => 'Siti Junior',
            'username' => 'siti',
            'email' => 'siti@kantor.com',
            'password' => $password,
            'role' => 'employee',
            'division_id' => $divisiIT->id,
            'annual_leave_quota' => 0, // Belum dapat cuti
            'join_date' => date('Y-m-d'), // Baru masuk hari ini
        ]);

        // 7. Buat HARI LIBUR Contoh (Fitur Opsional)
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