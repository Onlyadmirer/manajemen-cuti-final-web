<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf; // <--- PENTING: Import Library PDF

class LeaveRequestController extends Controller
{
    // 1. Tampilkan Riwayat Cuti
    public function index()
    {
        $requests = LeaveRequest::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('employee.leaves.index', compact('requests'));
    }

    // 2. Form Pengajuan
    public function create()
    {
        return view('employee.leaves.create');
    }

    // 3. Proses Simpan
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

        // A. Validasi Cuti Tahunan (H-3)
        if ($request->leave_type == 'annual') {
            if ($startDate->diffInDays(now()) < 3 && $startDate->isFuture()) {
                return back()->withErrors(['start_date' => 'Pengajuan Cuti Tahunan minimal H-3.'])->withInput();
            }
        }

        // B. Validasi Cuti Sakit (Wajib File)
        if ($request->leave_type == 'sick' && !$request->hasFile('attachment')) {
            return back()->withErrors(['attachment' => 'Wajib upload surat dokter.'])->withInput();
        }

        // C. Hitung Hari Kerja
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

        // D. Cek Kuota
        if ($request->leave_type == 'annual' && $user->annual_leave_quota < $totalDays) {
            return back()->withErrors(['annual_leave_quota' => "Kuota tidak cukup."])->withInput();
        }

        // E. Cek Overlap
        $overlap = LeaveRequest::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved_by_leader', 'approved'])
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                      ->orWhereBetween('end_date', [$startDate, $endDate]);
            })->exists();

        if ($overlap) {
            return back()->withErrors(['start_date' => 'Anda sudah mengajukan cuti di tanggal ini.'])->withInput();
        }

        // Simpan File
        $path = null;
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('attachments', 'public');
        }

        // Kurangi Kuota
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

    // 4. Batalkan Cuti
    public function destroy(LeaveRequest $leaveRequest) // Ganti nama parameter biar sesuai route resource
    {
        // Karena route resource menggunakan parameter {leaf}, Laravel kadang bingung bindingnya.
        // Kita pakai ID manual saja kalau error, tapi coba ini dulu.
        // Jika error "404 not found", ganti parameter jadi ($id) lalu $leaveRequest = LeaveRequest::findOrFail($id);
        
        if (Auth::id() !== $leaveRequest->user_id) {
            abort(403);
        }

        if ($leaveRequest->status !== 'pending') {
            return back()->with('error', 'Tidak bisa dibatalkan karena sudah diproses.');
        }

        if ($leaveRequest->leave_type == 'annual') {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $user->increment('annual_leave_quota', $leaveRequest->total_days);
        }

        $leaveRequest->status = 'cancelled'; 
        $leaveRequest->save(); 
        // Atau $leaveRequest->delete() jika mau hapus permanen dari database.
        // Tapi di controller sebelumnya kita pakai soft delete status 'cancelled'.
        // Kalau mau hapus barisnya: $leaveRequest->delete();

        // Sesuai kode sebelumnya kita ubah status jadi cancelled, tapi route resource destroy biasanya delete.
        // Mari kita sepakati HAPUS data saja biar tabel bersih.
        $leaveRequest->delete();

        return redirect()->route('leaves.index')->with('success', 'Pengajuan dibatalkan.');
    }

    // 5. DOWNLOAD PDF (FUNGSI BARU)
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