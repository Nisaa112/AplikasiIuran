<?php

use App\Http\Controllers\Api\LaporanApiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PembayaranIuranController;
use App\Http\Controllers\PengeluaranController;
// use App\Http\Controllers\TipeIuranController; // Hapus jika tidak dipakai
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// ===============================================
// RUTE PUBLIK (TIDAK PERLU LOGIN)
// ===============================================
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


// ===============================================
// RUTE YANG DILINDUNGI (WAJIB LOGIN DENGAN JWT)
// ===============================================
Route::middleware('auth:api')->group(function () {

    // Rute untuk otentikasi (logout, refresh token, info user)
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/me', [AuthController::class, 'me']); // Tambahkan ini, sangat berguna untuk frontend

    // Rute untuk mengelola data inti aplikasi
    // SEMUA RUTE DI BAWAH INI SEKARANG AMAN
    
    // PEMBAYARAN IURAN
    // URL: /api/pembayaran-iuran
    Route::apiResource('pembayaran-iuran', PembayaranIuranController::class);
    
    // MEMBER
    // URL: /api/members
    Route::apiResource('members', MemberController::class);
    
    // PENGELUARAN
    // URL: /api/pengeluaran
    Route::apiResource('pengeluaran', PengeluaranController::class);

    Route::get('/laporana', [LaporanApiController::class, 'getLaporan']);

});