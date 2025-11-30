<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\User;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    // 1. TAMPILKAN DAFTAR DIVISI [cite: 890-894]
    public function index()
    {
        $divisions = Division::with('manager')->withCount('members')->get();
        return view('admin.divisions.index', compact('divisions'));
    }

    // 2. HALAMAN TAMBAH DIVISI [cite: 895-901]
    public function create()
    {
        // Cari User role Manager yang belum punya divisi
        $availableManagers = User::where('role', 'division_manager')
            ->whereDoesntHave('managedDivision')
            ->get();

        return view('admin.divisions.create', compact('availableManagers'));
    }

    // 3. PROSES SIMPAN DIVISI BARU [cite: 902-906]
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:divisions,name',
            'manager_id' => 'required|exists:users,id',
            'description' => 'nullable|string',
        ]);

        // Buat Divisi
        $division = Division::create($request->all());

        // Update User Manager agar terikat ke divisi ini
        $manager = User::find($request->manager_id);
        $manager->update(['division_id' => $division->id]);

        return redirect()->route('divisions.index')->with('success', 'Divisi berhasil dibuat!');
    }

    // 4. HALAMAN EDIT DIVISI [cite: 907-911]
    public function edit(Division $division)
    {
        // Manager nganggur + Manager divisi ini sekarang
        $availableManagers = User::where('role', 'division_manager')
            ->whereDoesntHave('managedDivision')
            ->orWhere('id', $division->manager_id)
            ->get();

        return view('admin.divisions.edit', compact('division', 'availableManagers'));
    }

    // 5. PROSES UPDATE DIVISI [cite: 912-915]
    public function update(Request $request, Division $division)
    {
        $request->validate([
            'name' => 'required|unique:divisions,name,' . $division->id,
            'manager_id' => 'required|exists:users,id',
            'description' => 'nullable|string',
        ]);

        $division->update($request->all());

        // Update Manager Baru
        $newManager = User::find($request->manager_id);
        if ($newManager) {
            $newManager->update(['division_id' => $division->id]);
        }

        return redirect()->route('divisions.index')->with('success', 'Divisi berhasil diperbarui!');
    }

    // 6. HAPUS DIVISI [cite: 916-920]
    public function destroy(Division $division)
    {
        $division->delete();
        return redirect()->route('divisions.index')->with('success', 'Divisi berhasil dihapus!');
    }
}