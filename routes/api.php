<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PembayaranIuranController;
use App\Http\Controllers\TipeIuranController;
use Illuminate\Support\Facades\Route;

// AUTHENTICATION
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware(['auth:api']);
Route::post('/refresh', [AuthController::class, 'refresh'])->middleware(['auth:api']);

// PEMBAYARAN IURAN
Route::apiResource('bayarIuran', PembayaranIuranController::class);
// MEMBER
Route::apiResource('member', MemberController::class);