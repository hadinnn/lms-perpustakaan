<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Try login with email or NIP
        $field = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'nip';

        $loginData = [
            $field     => $credentials['login'],
            'password' => $credentials['password'],
            'is_active' => true,
        ];

        if (Auth::attempt([$field => $credentials['login'], 'password' => $credentials['password']])) {
            if (!Auth::user()->is_active) {
                Auth::logout();
                return back()->withErrors(['login' => 'Akun Anda tidak aktif. Hubungi administrator.'])->withInput();
            }
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()
            ->withErrors(['login' => 'Email/NIP atau password tidak sesuai.'])
            ->withInput($request->only('login'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
