<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Buat constructor untuk menerapkan middleware.
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Registrasi user baru hanya dengan nama, serial number, dan password.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'serial_number' => $request->serial_number,
            'password' => Hash::make($request->password),
        ]);

        $token = auth('api')->login($user);
        
        return $this->respondWithToken($token);
    }

    /**
     * Login user menggunakan serial number dan password.
     */
    public function login(Request $request)
    {
        $request->validate([
            'serial_number' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('serial_number', 'password');

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Dapatkan detail user yang sedang login.
     */
    public function me()
    {
        return response()->json(auth('api')->user());
    }

    /**
     * Logout user.
     */
    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh sebuah token.
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * Dapatkan struktur response token.
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => auth('api')->user()
        ]);
    }
}