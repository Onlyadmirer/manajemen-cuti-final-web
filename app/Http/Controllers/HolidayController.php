<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;

/**
 * Controller untuk mengelola data hari libur nasional/cuti bersama
 * Digunakan dalam perhitungan hari kerja efektif pengajuan cuti
 * 
 * @package App\Http\Controllers
 * @author Sistem Manajemen Cuti
 */
class HolidayController extends Controller
{
    /**
     * Menampilkan daftar hari libur yang telah didaftarkan
     * Diurutkan dari tanggal terbaru
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Mengurutkan berdasarkan tanggal dari yang terbaru
        $holidays = Holiday::orderBy('holiday_date', 'desc')->paginate(10);
        return view('admin.holidays.index', compact('holidays'));
    }

    /**
     * Menampilkan form untuk menambahkan hari libur baru
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.holidays.create');
    }

    /**
     * Menyimpan data hari libur baru ke database
     * Tanggal harus unik (tidak boleh duplikat)
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'holiday_date' => 'required|date|unique:holidays,holiday_date',
            'description' => 'required|string|max:255',
        ]);

        Holiday::create($request->all());

        return redirect()->route('holidays.index')->with('success', 'Hari libur berhasil ditambahkan!');
    }

    /**
     * Menampilkan form untuk mengedit data hari libur
     * 
     * @param  \App\Models\Holiday  $holiday
     * @return \Illuminate\View\View
     */
    public function edit(Holiday $holiday)
    {
        return view('admin.holidays.edit', compact('holiday'));
    }

    /**
     * Memperbarui data hari libur di database
     * Validasi unik kecuali untuk data yang sedang diedit
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Holiday  $holiday
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Holiday $holiday)
    {
        $request->validate([
            'holiday_date' => 'required|date|unique:holidays,holiday_date,' . $holiday->id,
            'description' => 'required|string|max:255',
        ]);

        $holiday->update($request->all());

        return redirect()->route('holidays.index')->with('success', 'Data hari libur diperbarui!');
    }

    /**
     * Menghapus data hari libur dari database
     * 
     * @param  \App\Models\Holiday  $holiday
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Holiday $holiday)
    {
        $holiday->delete();
        return redirect()->route('holidays.index')->with('success', 'Hari libur dihapus!');
    }
}