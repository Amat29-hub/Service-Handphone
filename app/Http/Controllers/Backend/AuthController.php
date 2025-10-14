<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * 🔹 Tampilkan halaman login
     */
    public function showLoginForm()
    {
        return view('page.backend.auth.login');
    }

    /**
     * 🔹 Proses login user
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Coba login
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();

            // Redirect sesuai role
            if (in_array($user->role, ['admin', 'teknisi'])) {
                return redirect()->route('dashboard')->with('success', 'Selamat datang di dashboard!');
            }

            if ($user->role === 'user' || $user->role === 'pelanggan') {
                return redirect()->route('dashboard')->with('success', 'Login berhasil!');
            }

            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah!',
        ])->onlyInput('email');
    }

    /**
     * 🔹 Logout user
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah logout.');
    }
}