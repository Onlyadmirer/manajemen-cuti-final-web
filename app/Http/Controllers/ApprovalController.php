<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Services\FonnteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApprovalController extends Controller
{
    protected $fonnteService;

    public function __construct(FonnteService $fonnteService)
    {
        $this->fonnteService = $fonnteService;
    }

    // 1. TAMPILKAN DAFTAR ANTRIAN APPROVAL [cite: 954-956]
    public function index()
    {
        $user = Auth::user();
        $approvals = collect(); // Koleksi kosong

        // LOGIKA MANAGER: Lihat pending dari bawahan satu divisi
        if ($user->role === 'division_manager' && $user->managedDivision) {
            $approvals = LeaveRequest::where('status', 'pending')
                ->whereHas('user', function ($query) use ($user) {
                    $query->where('division_id', $user->managedDivision->id)
                          ->where('role', 'employee'); // Hanya staff biasa
                })
                ->orderBy('created_at', 'asc')
                ->get();
        }

        // LOGIKA HRD: Lihat (Approved by Leader) ATAU (Pending dari Manager)
        if ($user->role === 'hr') {
            $approvals = LeaveRequest::where('status', 'approved_by_leader') // Sudah lolos manager
                ->orWhere(function ($query) {
                    $query->where('status', 'pending')
                          ->whereHas('user', function ($q) {
                              $q->where('role', 'division_manager'); // Cuti manager langsung ke HR
                          });
                })
                ->orderBy('created_at', 'asc')
                ->get();
        }

        return view('approvals.index', compact('approvals'));
    }

    // 2. PROSES APPROVE (SETUJU)
    public function approve(LeaveRequest $leaveRequest)
    {
        $user = Auth::user();

        // Jika Manager yang approve
        if ($user->role === 'division_manager') {
            $leaveRequest->update([
                'status' => 'approved_by_leader',
                'leader_approver_id' => $user->id,
            ]);
            return back()->with('success', 'Pengajuan disetujui. Melanjutkan ke HRD.');
        }

        // Jika HRD yang approve (FINAL) [cite: 957-960]
        if ($user->role === 'hr') {
            $leaveRequest->update([
                'status' => 'approved',
                'hr_approver_id' => $user->id,
                'approved_at' => now(),
            ]);

            // Kirim notifikasi WhatsApp ke emergency contact
            $this->fonnteService->sendLeaveNotification($leaveRequest, 'approved');

            return back()->with('success', 'Pengajuan disetujui sepenuhnya (Final). Notifikasi telah dikirim.');
        }
        
        abort(403);
    }

    // 3. PROSES REJECT (TOLAK)
    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        $request->validate([
            'rejection_reason' => 'required|string|min:5', // Wajib isi alasan [cite: 1016]
        ]);

        $user = Auth::user();

        // Kembalikan kuota cuti ke karyawan karena ditolak [cite: 845]
        if ($leaveRequest->leave_type == 'annual') {
            $leaveRequest->user->increment('annual_leave_quota', $leaveRequest->total_days);
        }

        $leaveRequest->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            // Catat siapa yang menolak
            'leader_approver_id' => ($user->role === 'division_manager') ? $user->id : $leaveRequest->leader_approver_id,
            'hr_approver_id' => ($user->role === 'hr') ? $user->id : $leaveRequest->hr_approver_id,
        ]);

        // Kirim notifikasi WhatsApp ke emergency contact
        $this->fonnteService->sendLeaveNotification($leaveRequest, 'rejected', $request->rejection_reason);

        return back()->with('success', 'Pengajuan ditolak. Kuota telah dikembalikan. Notifikasi telah dikirim.');
    }
}