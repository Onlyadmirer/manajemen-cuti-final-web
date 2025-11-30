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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama Lengkap
            $table->string('username')->unique(); // Login pakai username
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            
            // --- FIELD KHUSUS PROJECT 8 ---
            // Role: admin, hr, division_manager, employee
            $table->string('role')->default('employee'); 
            
            // Profil Tambahan
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('profile_photo_path')->nullable();
            
            // Logika Cuti
            $table->integer('annual_leave_quota')->default(12); // Default 12 hari [cite: 827]
            $table->date('join_date')->nullable(); // Untuk cek masa kerja < 1 tahun
            
            // Kita BELUM tambahkan division_id di sini agar tidak error
            // (karena tabel divisi belum dibuat saat kode ini jalan)

            $table->rememberToken();
            $table->timestamps();
        });

        // Tabel Reset Password (Bawaan Laravel)
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Tabel Sessions (Bawaan Laravel)
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};