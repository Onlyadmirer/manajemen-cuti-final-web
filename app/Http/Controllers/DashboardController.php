<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\LeaveRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // 1. Logika untuk ADMIN [cite: 971-978]
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

        // 2. Logika untuk HRD [cite: 989-994]
        if ($user->hasRole('hr')) {
            // HRD perlu melihat pengajuan yang sudah disetujui Leader, ATAU pengajuan dari Manager langsung
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

        // 3. Logika untuk MANAGER (Ketua Divisi) [cite: 985-988]
        if ($user->hasRole('division_manager')) {
            $division = $user->managedDivision;
            
            // Ambil pengajuan pending HANYA dari anggota divisinya
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

        // 4. Logika untuk EMPLOYEE (Karyawan) [cite: 979-984]
        // Default role
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