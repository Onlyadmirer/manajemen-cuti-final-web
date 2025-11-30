<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan kolom division_id setelah kolom role
            // Jika divisi dihapus, user tidak ikut terhapus (set null)
            $table->foreignId('division_id')
                  ->nullable()
                  ->after('role')
                  ->constrained('divisions')
                  ->onDelete('set null'); 
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus foreign key dulu baru kolomnya (kebalikan dari up)
            $table->dropForeign(['division_id']);
            $table->dropColumn('division_id');
        });
    }
};