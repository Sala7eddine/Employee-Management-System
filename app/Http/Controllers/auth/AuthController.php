<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Personnel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Hash;
use User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('pages.auth.loginT');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function ToLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = Utilisateur::with(['personnel', 'role']) // Eager load relationships
            ->where('email', $credentials['email'])
            ->first();

        // dd([
        //     'input_password' => $credentials['password'],
        //     'db_password' => $user->mot_de_passe_hash,
        //     'check_result' => Hash::check($credentials['password'], $user->mot_de_passe_hash)
        // ]);

        if ($user && Hash::check($credentials['password'], $user->mot_de_passe_hash)) {
            Auth::login($user);
            $request->session()->regenerate();
            return to_route('employees.index');

        }

        return back()->withErrors([
            'email' => 'Invalid credentials',
        ])->onlyInput('email')->with($credentials);
    }

    public function logout()
    {
        Auth::logout();
        return to_route('login');
    }
}
