<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;


class AuthController extends Controller
{
    /**
     * Display the login view.
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        if ($request->user()->role === 'admin') {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Display the registration view.
     */
    public function createRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function storeRegister(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'nim' => $request->nim,
            'role' => 'student', // Default role
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect(route('dashboard'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}