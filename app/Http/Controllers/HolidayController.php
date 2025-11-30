<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    // 1. TAMPILKAN DAFTAR HARI LIBUR
    public function index()
    {
        // Urutkan dari tanggal terbaru
        $holidays = Holiday::orderBy('holiday_date', 'desc')->paginate(10);
        return view('admin.holidays.index', compact('holidays'));
    }

    // 2. FORM TAMBAH
    public function create()
    {
        return view('admin.holidays.create');
    }

    // 3. SIMPAN DATA
    public function store(Request $request)
    {
        $request->validate([
            'holiday_date' => 'required|date|unique:holidays,holiday_date', // Tanggal harus unik
            'description' => 'required|string|max:255',
        ]);

        Holiday::create($request->all());

        return redirect()->route('holidays.index')->with('success', 'Hari libur berhasil ditambahkan!');
    }

    // 4. FORM EDIT
    public function edit(Holiday $holiday)
    {
        return view('admin.holidays.edit', compact('holiday'));
    }

    // 5. UPDATE DATA
    public function update(Request $request, Holiday $holiday)
    {
        $request->validate([
            // Unik kecuali punya diri sendiri
            'holiday_date' => 'required|date|unique:holidays,holiday_date,' . $holiday->id, 
            'description' => 'required|string|max:255',
        ]);

        $holiday->update($request->all());

        return redirect()->route('holidays.index')->with('success', 'Data hari libur diperbarui!');
    }

    // 6. HAPUS DATA
    public function destroy(Holiday $holiday)
    {
        $holiday->delete();
        return redirect()->route('holidays.index')->with('success', 'Hari libur dihapus!');
    }
}