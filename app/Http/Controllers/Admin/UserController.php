<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. TAMBAHKAN VALIDASI UNTUK EMAIL
        $request->validate([
            'name' => 'required|string|max:255',
            // Rule 'email' memastikan formatnya benar.
            // Rule 'unique:users' memastikan tidak ada email yang sama di tabel users.
            'email' => 'nullable|string|email|max:255|unique:users', 
            'password' => 'required|string|min:6|confirmed',
        ]);

        // 2. TAMBAHKAN 'email' SAAT MEMBUAT USER BARU
        User::create([
            'name' => $request->name,
            'email' => $request->email, // Tambahkan ini
            'password' => Hash::make($request->password),
            'serial_number' => "IUR" . '-' . strtoupper(Str::random(6)), // Contoh logika pembuatan serial number
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User baru dengan nama <strong>' . $request->name . '</strong> berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // PENTING: Tambahkan pengecekan agar user tidak bisa menghapus dirinya sendiri
        if (auth()->id() == $user->id) {
            return redirect()->route('users.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Hapus user
        $user->delete();

        // Redirect kembali ke halaman daftar user dengan pesan sukses
        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}
