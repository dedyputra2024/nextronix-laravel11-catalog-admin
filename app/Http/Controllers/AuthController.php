<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = $request->user();

            if ($user->is_admin || $user->hasAnyRole(['admin', 'super-admin', 'staff'])) {
                return redirect()->intended(route('admin.dashboard'));
            }

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'Login hanya tersedia untuk admin atau staff.',
            ])->onlyInput('email');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return redirect()->route('login')->with('info', 'Registrasi customer dinonaktifkan. Login hanya untuk admin/staff.');
    }

    public function register(Request $request)
    {
        return redirect()->route('login')->with('info', 'Registrasi customer dinonaktifkan. Login hanya untuk admin/staff.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Logout berhasil.');
    }
}
