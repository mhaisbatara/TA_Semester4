<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'user',
        ]);

        $token = $user->createToken('flutter_app')->plainTextToken;

        return response()->json([
            'message' => 'Registrasi berhasil',
            'token'   => $token,
            'user'    => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role,
            ],
        ], 201);
    }

    public function login(Request $request)
{
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return response()->json(['message' => 'Email atau password salah'], 401);
    }

    // Fix: ganti $2b$ → $2y$ agar Laravel bisa verifikasi bcrypt dari Node.js
    $hashedPassword = str_replace('$2b$', '$2y$', $user->password);

    if (!\Illuminate\Support\Facades\Hash::check($request->password, $hashedPassword)) {
        return response()->json(['message' => 'Email atau password salah'], 401);
    }

    if ($user->role !== 'user') {
        return response()->json(['message' => 'Akses ditolak'], 403);
    }

    $token = $user->createToken('flutter_app')->plainTextToken;

    return response()->json([
        'message' => 'Login berhasil',
        'token'   => $token,
        'user'    => [
            'id'    => (string) $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'role'  => $user->role,
        ],
    ]);
}

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout berhasil']);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
