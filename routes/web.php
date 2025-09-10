<?php

use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PembayaranIuranController;
use App\Http\Controllers\PengeluaranController;
use App\Models\PembayaranIuran;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ===================================================================
// RUTE UNTUK PENGGUNA YANG BELUM LOGIN (TAMU)
// ===================================================================
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
});


// ===================================================================
// RUTE UNTUK PENGGUNA YANG SUDAH LOGIN
// ===================================================================
Route::middleware('auth')->group(function () {
    // Rute Logout
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    
    // Rute Dashboard/Halaman utama setelah login
    Route::get('/dashboard', [MemberController::class, 'index'])->name('dashboard');

    // MEMBER
    Route::get('/member', [MemberController::class, 'index'])->name('member.index');
    Route::get('member/add', [MemberController::class, 'create'])->name('member.create');

    Route::post('member', [MemberController::class, 'store'])->name('member.store');
    Route::get('member/{id}/edit', [MemberController::class, 'edit'])->name('member.edit');
    Route::patch('member/{id}/edit', [MemberController::class, 'update'])->name('member.update');

    Route::delete('member/{id}/delete', [MemberController::class, 'destroy'])->name('member.destroy');

    // PEMBAYARAN
    Route::get('/pembayaran', [PembayaranIuranController::class, 'index'])->name('pembayaran.index');

    Route::get('pembayaran/add', [PembayaranIuranController::class, 'create'])->name('pembayaran.create');
    Route::post('pembayaran', [PembayaranIuranController::class, 'store'])->name('pembayaran.store');

    Route::get('pembayaran/{id}/edit', [PembayaranIuranController::class, 'edit'])->name('pembayaran.edit');
    Route::patch('pembayaran/{id}/edit', [PembayaranIuranController::class, 'update'])->name('pembayaran.update');

    Route::delete('pembayaran/{id}/delete', [PembayaranIuranController::class, 'destroy'])->name('pembayaran.destroy');

    // PENGELUARAN
    Route::get('/pengeluaran', [PengeluaranController::class, 'index'])->name('pengeluaran.index');

    Route::get('pengeluaran/add', [PengeluaranController::class, 'create'])->name('pengeluaran.create');
    Route::post('pengeluaran', [PengeluaranController::class, 'store'])->name('pengeluaran.store');

    Route::get('pengeluaran/{id}/edit', [PengeluaranController::class, 'edit'])->name('pengeluaran.edit');
    Route::patch('pengeluaran/{id}/edit', [PengeluaranController::class, 'update'])->name('pengeluaran.update');

    Route::delete('pengeluaran/{id}/delete', [PengeluaranController::class, 'destroy'])->name('pengeluaran.destroy');

    // // LAPORAN
    // Route::get('/laporanBulanan/{tahun}/{bulan}', [LaporanController::class, 'laporanBulananWeb'])->name('laporan.index');
    // Route::get('/laporanAnggota/{tahun}/{bulan}', [LaporanController::class, 'laporanAnggotaWeb'])->name('laporan.anggota');
    // Route::get('/laporanKas', [LaporanController::class, 'laporanKasWeb'])->name('laporan.kas');

    // ========================================================
    // TAMBAHKAN INI: RUTE UNTUK MANAJEMEN USER
    // ========================================================
    // Satu baris ini akan otomatis membuat route untuk:
    // - users.index   (GET /users)
    // - users.create  (GET /users/create)
    // - users.store   (POST /users)
    // - users.edit    (GET /users/{user}/edit)
    // - users.update  (PUT/PATCH /users/{user})
    // - users.destroy (DELETE /users/{user})
    Route::resource('users', UserController::class);

    // Route untuk menampilkan halaman dashboard utama
    // URL: http://domain-anda.com/dashboard
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');

    // Route untuk mengekspor data ke Excel
    // URL: http://domain-anda.com/laporan/export/excel
    Route::get('/laporan/export/excel', [LaporanController::class, 'exportExcel'])->name('laporan.export.excel');

    // Route untuk mengekspor data ke PDF
    // URL: http://domain-anda.com/laporan/export/pdf
    Route::get('/laporan/export/pdf', [LaporanController::class, 'exportPDF'])->name('laporan.export.pdf');


});


// ===================================================================
// RUTE UTAMA APLIKASI
// ===================================================================
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard'); // atau redirect()->route('member.index');
    }
    return redirect()->route('login');
});