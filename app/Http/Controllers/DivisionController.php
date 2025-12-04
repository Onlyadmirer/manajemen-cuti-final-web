<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Controller untuk mengelola data divisi
 * Menangani CRUD operasi divisi dan assignment manager
 * 
 * @package App\Http\Controllers
 * @author Sistem Manajemen Cuti
 */
class DivisionController extends Controller
{
    /**
     * Menampilkan daftar semua divisi beserta manager dan jumlah anggota
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $divisions = Division::with('manager')->withCount('members')->get();
        return view('admin.divisions.index', compact('divisions'));
    }

    /**
     * Menampilkan form untuk membuat divisi baru
     * Mengambil daftar manager yang belum memiliki divisi
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Mencari user dengan role manager yang belum mengelola divisi manapun
        $availableManagers = User::where('role', 'division_manager')
            ->whereDoesntHave('managedDivision')
            ->get();

        return view('admin.divisions.create', compact('availableManagers'));
    }

    /**
     * Menyimpan data divisi baru ke database
     * Menghubungkan manager dengan divisi yang dibuat
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:divisions,name',
            'manager_id' => 'required|exists:users,id',
            'description' => 'nullable|string',
        ]);

        // Membuat data divisi baru
        $division = Division::create($request->all());

        // Mengupdate user manager agar terhubung dengan divisi ini
        $manager = User::find($request->manager_id);
        $manager->update(['division_id' => $division->id]);

        return redirect()->route('divisions.index')->with('success', 'Divisi berhasil dibuat!');
    }

    /**
     * Menampilkan form untuk mengedit data divisi
     * Mengambil daftar manager yang tersedia untuk dipilih
     * 
     * @param  \App\Models\Division  $division
     * @return \Illuminate\View\View
     */
    public function edit(Division $division)
    {
        // Mengambil manager yang belum mengelola divisi atau manager divisi saat ini
        $availableManagers = User::where('role', 'division_manager')
            ->whereDoesntHave('managedDivision')
            ->orWhere('id', $division->manager_id)
            ->get();

        return view('admin.divisions.edit', compact('division', 'availableManagers'));
    }

    /**
     * Memperbarui data divisi di database
     * Mengupdate assignment manager jika berubah
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Division  $division
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Division $division)
    {
        $request->validate([
            'name' => 'required|unique:divisions,name,' . $division->id,
            'manager_id' => 'required|exists:users,id',
            'description' => 'nullable|string',
        ]);

        $division->update($request->all());

        // Mengupdate manager baru untuk divisi ini
        $newManager = User::find($request->manager_id);
        if ($newManager) {
            $newManager->update(['division_id' => $division->id]);
        }

        return redirect()->route('divisions.index')->with('success', 'Divisi berhasil diperbarui!');
    }

    /**
     * Menghapus data divisi dari database
     * 
     * @param  \App\Models\Division  $division
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Division $division)
    {
        $division->delete();
        return redirect()->route('divisions.index')->with('success', 'Divisi berhasil dihapus!');
    }
}