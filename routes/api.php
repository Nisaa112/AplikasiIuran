<?php

use App\Http\Controllers\Api\LaporanApiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\historyController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PembayaranIuranController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\ProfilController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:api')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/me', [AuthController::class, 'me']);

    
    // PEMBAYARAN IURAN
    Route::apiResource('pembayaran-iuran', PembayaranIuranController::class);
    
    // MEMBER
    Route::apiResource('members', MemberController::class);
    
    // PENGELUARAN
    Route::apiResource('pengeluaran', PengeluaranController::class);
    
    // PROFIL (Singleton Resource)
    Route::get('/profil', [ProfilController::class, 'show']);
    Route::post('/profil', [ProfilController::class, 'save']); 
    Route::get('/profil/edit', [ProfilController::class, 'edit']); 
    Route::delete('/profil', [ProfilController::class, 'destroy']);

    Route::get('/laporana', [LaporanApiController::class, 'getLaporan']);

    Route::get('/histori', [historyController::class, 'index']);

});