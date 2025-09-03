<?php

use App\Http\Controllers\MemberController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// MEMBER
Route::get('/member', [MemberController::class, 'index']);

Route::get('member/add', [MemberController::class, 'create']);
Route::post('member', [MemberController::class, 'store']);

Route::get('member/{id}/edit', [MemberController::class, 'edit']);
Route::patch('member/{id}/edit', [MemberController::class, 'update']);

Route::patch('member/{id}/delete', [MemberController::class, 'destroy']);