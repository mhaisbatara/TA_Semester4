<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    /**
     * Tampilkan halaman lupa password.
     */
    public function showForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Step 1 — Verifikasi email admin.
     */
    public function verifyEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
        ]);

        // Cari user dengan email & role admin (MongoDB compatible)
        $admin = User::where('email', $request->email)
                     ->where('role', 'admin')
                     ->first();

        if (! $admin) {
            return back()
                ->withErrors(['email' => 'Email tidak ditemukan atau bukan akun admin.'])
                ->withInput();
        }

        // Simpan email terverifikasi ke session
        session(['verified_email' => $request->email]);

        // Redirect ke route yang sama, blade detect session lalu tampilkan step 2
        return redirect()->route('admin.forgot-password.form');
    }

    /**
     * Step 2 — Reset password baru.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'min:8', 'confirmed'],
        ], [
            'password.required'  => 'Password baru wajib diisi.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Validasi session (anti-manipulasi form)
        if (session('verified_email') !== $request->email) {
            session()->forget('verified_email');
            return redirect()->route('admin.forgot-password.form')
                             ->with('error', 'Sesi tidak valid. Silakan ulangi dari awal.');
        }

        $admin = User::where('email', $request->email)
                     ->where('role', 'admin')
                     ->first();

        if (! $admin) {
            return redirect()->route('admin.forgot-password.form')
                             ->with('error', 'Akun tidak ditemukan.');
        }

        // Update password — MongoDB compatible (gunakan save() bukan update())
        $admin->password = Hash::make($request->password);
        $admin->save();

        // Hapus session verifikasi, tandai sukses
        session()->forget('verified_email');
        session(['reset_success' => true]);

        return redirect()->route('admin.forgot-password.form');
    }
}
