<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('divisions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Nama Divisi (harus unik)
            $table->text('description')->nullable();
            
            // Ketua Divisi (Manager)
            // Relasi ke users.id. Nullable karena divisi baru mungkin belum ada ketuanya.
            // onDelete('set null') artinya jika user manager dihapus, kolom ini jadi kosong (bukan error).
            $table->foreignId('manager_id')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('divisions');
    }
};