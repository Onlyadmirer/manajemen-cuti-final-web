<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    use HasFactory;

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

    // Mengubah string tanggal di database menjadi objek Date di PHP
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime',
    ];

    // --- RELASI ---

    // 1. Pengajuan ini milik siapa?
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 2. Siapa Ketua Divisi yang menyetujui?
    public function leaderApprover()
    {
        return $this->belongsTo(User::class, 'leader_approver_id');
    }

    // 3. Siapa HRD yang menyetujui?
    public function hrApprover()
    {
        return $this->belongsTo(User::class, 'hr_approver_id');
    }
}