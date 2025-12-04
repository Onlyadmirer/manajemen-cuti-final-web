<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Division - Representasi data divisi/departemen
 * 
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int|null $manager_id
 * 
 * @package App\Models
 * @author Sistem Manajemen Cuti
 */
class Division extends Model
{
    use HasFactory;

    /**
     * Kolom yang dapat diisi secara massal
     * 
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'manager_id',
    ];

    /**
     * ========================================
     * RELASI DATABASE
     * ========================================
     */

    /**
     * Relasi ke manager - Divisi dikelola oleh satu manager
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * Relasi ke anggota - Divisi memiliki banyak anggota/karyawan
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function members()
    {
        return $this->hasMany(User::class, 'division_id');
    }
}