<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\UserModel;

class AuthController extends Controller
{
    public function login()
    {
        // Jika sudah login, langsung redirect ke halaman admin
        if (Auth::check()) {
            return redirect('/admin');
        }

        return view('auth.login'); // Tampilkan form login
    }

    public function postLogin(Request $request)
    {
        // Validasi input
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Cari user berdasarkan username
        $user = UserModel::where('username', $request->username)->first();

        // Periksa apakah user ditemukan dan password cocok
        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);

            return response()->json([
                'status' => true,
                'message' => 'Login berhasil',
                'redirect' => url('/admin')
            ]);
        }

        // Login gagal
        return response()->json([
            'status' => false,
            'message' => 'Username atau password salah'
        ]);
    }

    public function logout(Request $request)
    {
        session()->flush();
        return redirect('/home');
    }
}
