<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TipeIuranController;
use Illuminate\Support\Facades\Route;

// AUTHENTICATION
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware(['auth:api']);
Route::post('/refresh', [AuthController::class, 'refresh'])->middleware(['auth:api']);

// TIPE IURAN
Route::apiResource('tipeIuran', TipeIuranController::class);