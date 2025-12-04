<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model LeaveRequest - Representasi data pengajuan cuti
 * 
 * @property int $id
 * @property int $user_id
 * @property string $leave_type (annual, sick)
 * @property \Carbon\Carbon $start_date
 * @property \Carbon\Carbon $end_date
 * @property int $total_days
 * @property string $reason
 * @property string|null $attachment_path
 * @property string|null $address_during_leave
 * @property string|null $emergency_contact
 * @property string $status (pending, approved_by_leader, approved, rejected, cancelled)
 * @property int|null $leader_approver_id
 * @property int|null $hr_approver_id
 * @property string|null $rejection_reason
 * @property \Carbon\Carbon|null $approved_at
 * 
 * @package App\Models
 * @author Sistem Manajemen Cuti
 */
class LeaveRequest extends Model
{
    use HasFactory;

    /**
     * Kolom yang dapat diisi secara massal
     * 
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'leave_type', // annual, sick
        'start_date',
        'end_date',
        'total_days',
        'reason',
        'attachment_path',
        'address_during_leave',
        'emergency_contact',
        'status', // pending, approved_by_leader, approved, rejected, cancelled
        'leader_approver_id',
        'hr_approver_id',
        'rejection_reason',
        'approved_at',
    ];

    /**
     * Tipe casting untuk kolom tanggal
     * 
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime',
    ];

    /**
     * ========================================
     * RELASI DATABASE
     * ========================================
     */

    /**
     * Relasi ke user - Pengajuan cuti milik satu karyawan
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke approver manager - Manager yang menyetujui pengajuan
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function leaderApprover()
    {
        return $this->belongsTo(User::class, 'leader_approver_id');
    }

    /**
     * Relasi ke approver HRD - HRD yang memberikan persetujuan final
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hrApprover()
    {
        return $this->belongsTo(User::class, 'hr_approver_id');
    }
}