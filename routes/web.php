<?php

use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\MemberController;

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

    // RUTE MANUAL MEMBER ANDA KITA PINDAHKAN KE DALAM SINI
    // Tidak ada yang perlu diubah dari rute-rute ini, hanya lokasinya saja.
    
    // URL: /member -> menampilkan daftar member
    Route::get('/member', [MemberController::class, 'index'])->name('member.index');

    // URL: /member/add -> menampilkan form tambah
    Route::get('member/add', [MemberController::class, 'create'])->name('member.create');
    
    // URL: /member (POST) -> menyimpan data baru
    Route::post('member', [MemberController::class, 'store'])->name('member.store');

    // URL: /member/{id}/edit -> menampilkan form edit
    Route::get('member/{id}/edit', [MemberController::class, 'edit'])->name('member.edit');
    
    // URL: /member/{id}/edit (PATCH) -> mengupdate data
    Route::patch('member/{id}/edit', [MemberController::class, 'update'])->name('member.update');

    // URL: /member/{id}/delete (PATCH) -> menghapus data
    // Catatan: Biasanya ini menggunakan method DELETE, tapi kita ikuti rute Anda.
    Route::delete('member/{id}/delete', [MemberController::class, 'destroy'])->name('member.destroy');

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