<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Model User - Representasi data pengguna/karyawan
 * 
 * @property int $id
 * @property string $name
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $role (admin, hr, division_manager, employee)
 * @property int|null $division_id
 * @property string|null $phone
 * @property string|null $address
 * @property int $annual_leave_quota
 * @property \Carbon\Carbon|null $join_date
 * 
 * @package App\Models
 * @author Sistem Manajemen Cuti
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Kolom yang dapat diisi secara massal (Mass Assignment Protection)
     * 
     * @var array<int, string>
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

    /**
     * Kolom yang disembunyikan dari serialisasi
     * 
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Tipe casting untuk kolom tertentu
     * 
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'join_date' => 'date',
        ];
    }

    /**
     * ========================================
     * RELASI DATABASE
     * ========================================
     */

    /**
     * Relasi ke divisi - User adalah anggota dari satu divisi
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id');
    }

    /**
     * Relasi divisi yang dikelola - Manager mengelola satu divisi
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function managedDivision()
    {
        return $this->hasOne(Division::class, 'manager_id');
    }

    /**
     * Relasi ke pengajuan cuti - User memiliki banyak pengajuan cuti
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    /**
     * ========================================
     * HELPER METHODS
     * ========================================
     */
    
    /**
     * Memeriksa apakah user memiliki role tertentu
     * 
     * @param  string  $role
     * @return bool
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }
}