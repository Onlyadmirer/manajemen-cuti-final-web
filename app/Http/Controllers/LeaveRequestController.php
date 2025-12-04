<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * Controller untuk mengelola pengajuan cuti karyawan
 * 
 * @package App\Http\Controllers
 * @author Sistem Manajemen Cuti
 */
class LeaveRequestController extends Controller
{
    /**
     * Menampilkan daftar riwayat pengajuan cuti karyawan
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $requests = LeaveRequest::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('employee.leaves.index', compact('requests'));
    }

    /**
     * Menampilkan form pengajuan cuti baru
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('employee.leaves.create');
    }

    /**
     * Memproses dan menyimpan pengajuan cuti baru
     * Melakukan validasi H-3 untuk cuti tahunan, penghitungan hari kerja,
     * pengecekan kuota, dan validasi overlap tanggal
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'leave_type' => 'required|in:annual,sick',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        // Validasi cuti tahunan harus diajukan minimal H-3
        if ($request->leave_type == 'annual') {
            $minDate = Carbon::now()->addDays(3)->startOfDay();
            if ($startDate->lt($minDate)) {
                return back()->withErrors(['start_date' => 'Pengajuan Cuti Tahunan minimal H-3 (3 hari sebelum tanggal mulai cuti).'])->withInput();
            }
        }

        // Validasi cuti sakit wajib melampirkan surat keterangan dokter
        if ($request->leave_type == 'sick' && !$request->hasFile('attachment')) {
            return back()->withErrors(['attachment' => 'Wajib upload surat dokter.'])->withInput();
        }

        // Menghitung total hari kerja (tidak termasuk weekend dan hari libur)
        $totalDays = 0;
        $period = \Carbon\CarbonPeriod::create($startDate, $endDate);
        $holidays = Holiday::pluck('holiday_date')->toArray();

        foreach ($period as $date) {
            if (!$date->isWeekend() && !in_array($date->format('Y-m-d'), $holidays)) {
                $totalDays++;
            }
        }

        if ($totalDays == 0) {
            return back()->withErrors(['start_date' => 'Tanggal yang dipilih hari libur semua.'])->withInput();
        }

        // Validasi kuota cuti tahunan karyawan
        if ($request->leave_type == 'annual' && $user->annual_leave_quota < $totalDays) {
            return back()->withErrors(['annual_leave_quota' => "Kuota tidak cukup."])->withInput();
        }

        // Pengecekan overlap dengan pengajuan cuti yang sudah ada
        $overlap = LeaveRequest::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved_by_leader', 'approved'])
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                      ->orWhereBetween('end_date', [$startDate, $endDate]);
            })->exists();

        if ($overlap) {
            return back()->withErrors(['start_date' => 'Anda sudah mengajukan cuti di tanggal ini.'])->withInput();
        }

        // Menyimpan file lampiran jika ada
        $path = null;
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('attachments', 'public');
        }

        // Mengurangi kuota cuti tahunan karyawan
        if ($request->leave_type == 'annual') {
            $user->decrement('annual_leave_quota', $totalDays);
        }

        LeaveRequest::create([
            'user_id' => $user->id,
            'leave_type' => $request->leave_type,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_days' => $totalDays,
            'reason' => $request->reason,
            'attachment_path' => $path,
            'address_during_leave' => $request->address_during_leave,
            'emergency_contact' => $request->emergency_contact,
            'status' => 'pending',
        ]);

        return redirect()->route('leaves.index')->with('success', 'Pengajuan berhasil dikirim!');
    }

    /**
     * Membatalkan pengajuan cuti yang masih berstatus pending
     * Mengembalikan kuota cuti jika jenis cuti adalah tahunan
     * 
     * @param  \App\Models\LeaveRequest  $leaveRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(LeaveRequest $leaveRequest)
    {
        
        if (Auth::id() !== $leaveRequest->user_id) {
            abort(403);
        }

        if ($leaveRequest->status !== 'pending') {
            return back()->with('error', 'Tidak bisa dibatalkan karena sudah diproses.');
        }

        // Mengembalikan kuota cuti tahunan jika dibatalkan
        if ($leaveRequest->leave_type == 'annual') {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $user->increment('annual_leave_quota', $leaveRequest->total_days);
        }

        // Menghapus data pengajuan cuti dari database
        $leaveRequest->delete();

        return redirect()->route('leaves.index')->with('success', 'Pengajuan dibatalkan.');
    }

    /**
     * Mengunduh surat persetujuan cuti dalam format PDF
     * Hanya tersedia untuk pengajuan dengan status disetujui
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function downloadPdf($id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id);

        if (Auth::id() !== $leaveRequest->user_id) {
            abort(403);
        }

        if ($leaveRequest->status !== 'approved') {
            return back()->with('error', 'Surat hanya tersedia jika status Disetujui HRD.');
        }

        $pdf = Pdf::loadView('employee.leaves.pdf', compact('leaveRequest'));
        return $pdf->download('Surat_Cuti_' . $leaveRequest->user->name . '.pdf');
    }
}