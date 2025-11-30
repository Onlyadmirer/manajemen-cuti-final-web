<?php

use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// SEMUA YANG BUTUH LOGIN
Route::middleware(['auth', 'verified'])->group(function () {
    
    // DASHBOARD (Semua role bisa akses, controller yang memilah)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // -------------------------------------------------------------
    // GRUP KHUSUS ADMIN (Hanya Admin yang boleh masuk)
    // -------------------------------------------------------------
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('divisions', DivisionController::class);
        Route::resource('users', UserController::class);
        Route::resource('holidays', HolidayController::class);
    });

    // -------------------------------------------------------------
    // GRUP KHUSUS KARYAWAN (Employee)
    // -------------------------------------------------------------
    Route::middleware(['role:employee'])->group(function () {
        Route::resource('leaves', LeaveRequestController::class)->only(['index', 'create', 'store', 'destroy']);
        Route::get('/leaves/{id}/pdf', [LeaveRequestController::class, 'downloadPdf'])->name('leaves.download_pdf');
    });

    // -------------------------------------------------------------
    // GRUP KHUSUS APPROVER (Manager & HRD)
    // -------------------------------------------------------------
    Route::middleware(['role:division_manager,hr'])->group(function () {
        Route::get('/approvals', [ApprovalController::class, 'index'])->name('approvals.index');
        Route::patch('/approvals/{leaveRequest}/approve', [ApprovalController::class, 'approve'])->name('approvals.approve');
        Route::patch('/approvals/{leaveRequest}/reject', [ApprovalController::class, 'reject'])->name('approvals.reject');
    });

    // PROFILE (Bawaan)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';