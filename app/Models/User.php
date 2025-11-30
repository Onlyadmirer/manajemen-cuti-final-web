<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Kolom yang boleh diisi secara massal (Mass Assignment)
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role', // admin, hr, division_manager, employee
        'division_id',
        'phone',
        'address',
        'profile_photo_path',
        'annual_leave_quota',
        'join_date',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'join_date' => 'date',
        ];
    }

    // --- RELASI (HUBUNGAN ANTAR TABEL) ---

    // 1. Karyawan (User) adalah anggota dari satu Divisi
    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id');
    }

    // 2. Manager (User) bisa mengelola satu Divisi
    public function managedDivision()
    {
        return $this->hasOne(Division::class, 'manager_id');
    }

    // 3. Karyawan (User) memiliki banyak Pengajuan Cuti
    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    // --- HELPER FUNCTION (BANTUAN) ---
    
    // Fungsi cek role biar gampang di kodingan nanti: $user->hasRole('admin')
    public function hasRole($role)
    {
        return $this->role === $role;
    }
}