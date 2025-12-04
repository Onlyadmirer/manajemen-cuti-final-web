<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Services\FonnteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller untuk mengelola persetujuan pengajuan cuti
 * Menangani approval dari Manager Divisi dan HRD
 * 
 * @package App\Http\Controllers
 * @author Sistem Manajemen Cuti
 */
class ApprovalController extends Controller
{
    /**
     * Instance service untuk notifikasi WhatsApp
     * @var FonnteService
     */
    protected $fonnteService;

    /**
     * Constructor - Inject FonnteService untuk notifikasi
     * 
     * @param  FonnteService  $fonnteService
     */
    public function __construct(FonnteService $fonnteService)
    {
        $this->fonnteService = $fonnteService;
    }

    /**
     * Menampilkan daftar pengajuan cuti yang menunggu persetujuan
     * Disesuaikan berdasarkan role (Manager atau HRD)
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $approvals = collect();

        // Manager hanya melihat pengajuan dari anggota divisinya
        if ($user->role === 'division_manager' && $user->managedDivision) {
            $approvals = LeaveRequest::where('status', 'pending')
                ->whereHas('user', function ($query) use ($user) {
                    $query->where('division_id', $user->managedDivision->id)
                          ->where('role', 'employee'); // Hanya staff biasa
                })
                ->orderBy('created_at', 'asc')
                ->get();
        }

        // HRD melihat pengajuan yang sudah disetujui manager atau langsung dari manager
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

    /**
     * Menyetujui pengajuan cuti
     * Manager: mengubah status menjadi approved_by_leader
     * HRD: mengubah status menjadi approved (final) dan mengirim notifikasi
     * 
     * @param  \App\Models\LeaveRequest  $leaveRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve(LeaveRequest $leaveRequest)
    {
        $user = Auth::user();

        // Proses persetujuan oleh Manager Divisi
        if ($user->role === 'division_manager') {
            $leaveRequest->update([
                'status' => 'approved_by_leader',
                'leader_approver_id' => $user->id,
            ]);
            return back()->with('success', 'Pengajuan disetujui. Melanjutkan ke HRD.');
        }

        // Proses persetujuan final oleh HRD
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

    /**
     * Menolak pengajuan cuti
     * Mengembalikan kuota cuti dan mengirim notifikasi penolakan
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LeaveRequest  $leaveRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        $request->validate([
            'rejection_reason' => 'required|string|min:5',
        ]);

        $user = Auth::user();

        // Mengembalikan kuota cuti tahunan karena pengajuan ditolak
        if ($leaveRequest->leave_type == 'annual') {
            $leaveRequest->user->increment('annual_leave_quota', $leaveRequest->total_days);
        }

        $leaveRequest->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'leader_approver_id' => ($user->role === 'division_manager') ? $user->id : $leaveRequest->leader_approver_id,
            'hr_approver_id' => ($user->role === 'hr') ? $user->id : $leaveRequest->hr_approver_id,
        ]);

        // Mengirim notifikasi penolakan via WhatsApp
        $this->fonnteService->sendLeaveNotification($leaveRequest, 'rejected', $request->rejection_reason);

        return back()->with('success', 'Pengajuan ditolak. Kuota telah dikembalikan. Notifikasi telah dikirim.');
    }
}