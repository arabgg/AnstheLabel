<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        $profile = UserModel::all();
    
        return view('admin.index', [
            'profile' => $profile
        ]);
    }

    public function edit_ajax(string $id)
    {
        $profile = UserModel::find($id);

        return view('profile.edit_ajax', [
            'profile' => $profile
        ]);
    }

    public function update_ajax(Request $request, $id)
    {
        // Validasi input
        $validated = $request->validate([
            'nama' => 'required|min:3|max:100',
            'email' => 'required|email',
            'no_telp' => 'required|min:3|max:50',
            'username' => 'required|min:5|max:50',
            'password' => 'nullable|min:4', 
        ]);
    
        // Temukan profile berdasarkan user_id
        $profile = UserModel::find($id);
    
        if ($profile) {
            // Update data lainnya
            $profile->nama = $validated['nama'];
            $profile->email = $validated['email'];
            $profile->no_telp = $validated['no_telp'];
            $profile->username = $validated['username'];
    
            // Jika password diisi, enkripsi dan simpan password baru
            if ($request->has('password') && !empty($request->password)) {
                $profile->password = Hash::make($request->password);
            }
    
            // Simpan data
            $profile->save();
    
            // Mengembalikan response sukses
            return response()->json([
                'status' => true,
                'message' => 'Profile berhasil diperbarui.',
            ]);
        }
    
        // Jika profile tidak ditemukan
        return response()->json([
            'status' => false,
            'message' => 'Pengguna tidak ditemukan.',
        ]);
    }
}
