<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Tampilkan halaman form login.
     */
    public function showLoginForm()
    {
        return view('auth.login'); // Pastikan Anda punya view di resources/views/auth/login.blade.php
    }

    public function username()
    {   
        return 'serial_number';
    }


    /**
     * Tangani permintaan login.
     */
    public function login(Request $request)
    {
        $request->validate([
            'serial_number' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = [
            'serial_number' => $request->serial_number,
            'password' => $request->password,
        ];

        // dd(Auth::attempt($credentials));

        if (Auth::guard('web')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            // Selalu redirect ke member
            return redirect('/member');
        }

        return back()->withErrors([
            'serial_number' => 'Serial number atau password salah.',
        ])->onlyInput('serial_number');
    }


    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('login');
    }
}