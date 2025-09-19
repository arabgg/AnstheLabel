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
        if (Auth::check()) {
            return redirect('/admin');
        }

        return view('auth.login');
    }

    public function postLogin(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = UserModel::where('username', $request->username)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);

            return response()->json([
                'status' => true,
                'message' => 'Login berhasil',
                'redirect' => url('/admin')
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Username atau password salah'
        ]);
    }

    public function changePasswordForm()
    {
        return view('auth.change-password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        /** @var \App\Models\UserModel $user */
        $user = Auth::user();

        if (!$user->changePassword($request->current_password, $request->new_password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai']);
        }

        return redirect()->back()->with('success', 'Password berhasil diubah');
    }

    public function logout(Request $request)
    {
        session()->flush();
        return redirect('/home');
    }
}
