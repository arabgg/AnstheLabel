@extends('admin.layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-10 bg-white p-6 rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-6 text-center">Ganti Password</h2>

    {{-- Pesan sukses --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Pesan error --}}
    @if($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('auth.change-password.update') }}">
        @csrf

        {{-- Password Lama --}}
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Password Lama</label>
            <input type="password" name="current_password"
                   class="w-full border p-2 rounded mt-1 focus:ring focus:ring-blue-300" 
                   placeholder="Masukkan password lama" required>
        </div>

        {{-- Password Baru --}}
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Password Baru</label>
            <input type="password" name="new_password"
                   class="w-full border p-2 rounded mt-1 focus:ring focus:ring-blue-300" 
                   placeholder="Masukkan password baru" required>
        </div>

        {{-- Konfirmasi Password Baru --}}
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Konfirmasi Password Baru</label>
            <input type="password" name="new_password_confirmation"
                   class="w-full border p-2 rounded mt-1 focus:ring focus:ring-blue-300" 
                   placeholder="Ulangi password baru" required>
        </div>

        {{-- Tombol Submit --}}
        <button type="submit" 
                class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
            Simpan Password
        </button>
    </form>
</div>
@endsection
