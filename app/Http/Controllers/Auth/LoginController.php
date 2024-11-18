<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validasi input login
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Temukan user berdasarkan username
        $user = User::where('username', $request->username)->first();

        // Check apakah user ada dan password sesuai (tanpa hashing)
        if ($user && Hash::check($request->password, $user->password)) {
            // Log in user secara manual ke dalam session Auth
            Auth::login($user);
            // Redirect ke home dengan pesan sukses
            return redirect('/home')->with([
                'success' => 'Login Berhasil',
                'role' => $user->getRoleNames()->first(),
            ]);
        }

        // Jika username atau password salah
        return back()->withErrors(['invalid_credentials' => 'Either username or password is invalid.']);
    }

    public function logout()
    {
        Auth::logout(); // Logout pengguna dari session auth
        return redirect()->route('login');
    }
}
