<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\LeaveRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller untuk mengelola dashboard berdasarkan role pengguna
 * Menampilkan data statistik dan informasi sesuai dengan hak akses
 * 
 * @package App\Http\Controllers
 * @author Sistem Manajemen Cuti
 */
class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard sesuai dengan role pengguna
     * (Admin, HRD, Manager Divisi, atau Karyawan)
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Dashboard untuk Admin - Menampilkan statistik global sistem
        if ($user->hasRole('admin')) {
            $data = [
                'total_employees' => User::where('role', 'employee')->count(),
                'total_leave_requests_month' => LeaveRequest::whereMonth('created_at', Carbon::now()->month)->count(),
                'pending_approvals' => LeaveRequest::where('status', 'pending')->count(), // Global pending
                'new_employees' => User::where('role', 'employee')
                                        ->where('join_date', '>', Carbon::now()->subYear())
                                        ->get(),
                'total_divisions' => Division::count(),
            ];
            return view('admin.dashboard', $data);
        }

        // Dashboard untuk HRD - Menampilkan pengajuan yang perlu persetujuan final
        if ($user->hasRole('hr')) {
            // Mengambil pengajuan yang sudah disetujui manager atau pengajuan langsung dari manager
            $pendingFinal = LeaveRequest::where('status', 'approved_by_leader')
                ->orWhere(function($query) {
                    $query->where('status', 'pending')
                          ->whereHas('user', function($q) {
                              $q->where('role', 'division_manager');
                          });
                })->count();

            $data = [
                'total_requests_month' => LeaveRequest::whereMonth('created_at', Carbon::now()->month)->count(),
                'pending_final_approval' => $pendingFinal,
                'employees_on_leave' => LeaveRequest::where('status', 'approved')
                    ->whereDate('start_date', '<=', Carbon::now())
                    ->whereDate('end_date', '>=', Carbon::now())
                    ->get(),
                'divisions' => Division::all(),
            ];
            return view('hr.dashboard', $data);
        }

        // Dashboard untuk Manager Divisi - Menampilkan pengajuan dari anggota divisi
        if ($user->hasRole('division_manager')) {
            $division = $user->managedDivision;
            
            // Mengambil pengajuan pending dari karyawan dalam divisi yang dikelola
            $pendingVerification = 0;
            $members = collect([]);
            
            if ($division) {
                $pendingVerification = LeaveRequest::where('status', 'pending')
                    ->whereHas('user', function($q) use ($division) {
                        $q->where('division_id', $division->id)
                          ->where('role', 'employee'); // Hanya staff biasa
                    })->count();
                
                $members = $division->members;
            }

            $data = [
                'total_incoming_requests' => LeaveRequest::whereHas('user', function($q) use ($division) {
                        $q->where('division_id', $division->id ?? 0);
                    })->count(),
                'pending_verification' => $pendingVerification,
                'division_members' => $members,
                'division_name' => $division ? $division->name : 'Belum Ada Divisi',
            ];
            return view('manager.dashboard', $data);
        }

        // Dashboard untuk Karyawan - Menampilkan informasi pribadi dan kuota cuti
        $data = [
            'quota_remaining' => $user->annual_leave_quota, // Kuota realtime
            'sick_leave_count' => $user->leaveRequests()->where('leave_type', 'sick')->count(),
            'total_requests' => $user->leaveRequests()->count(),
            'division_name' => $user->division ? $user->division->name : '-',
            'manager_name' => $user->division && $user->division->manager ? $user->division->manager->name : '-',
        ];
        
        return view('employee.dashboard', $data);
    }
}