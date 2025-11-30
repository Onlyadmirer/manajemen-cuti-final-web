<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            
            // Siapa yang mengajukan? (Jika user dihapus, data cutinya ikut hilang)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Detail Cuti
            $table->enum('leave_type', ['annual', 'sick']); // Jenis: annual (tahunan) atau sick (sakit)
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('total_days'); // Total hari kerja
            $table->text('reason'); // Alasan cuti
            
            // Data Tambahan (Sakit & Kontak)
            $table->string('attachment_path')->nullable(); // Path file surat dokter
            $table->text('address_during_leave')->nullable(); 
            $table->string('emergency_contact')->nullable(); 
            
            // Status Approval
            // Flow: pending -> approved_by_leader -> approved (Final HR) -> rejected -> cancelled
            $table->string('status')->default('pending'); 
            
            // Siapa yang approve? (Bisa kosong jika belum diapprove)
            $table->foreignId('leader_approver_id')->nullable()->constrained('users'); // Ketua Divisi
            $table->foreignId('hr_approver_id')->nullable()->constrained('users'); // HRD
            
            $table->text('rejection_reason')->nullable(); // Alasan jika ditolak
            $table->timestamp('approved_at')->nullable(); // Kapan disetujui final
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
};