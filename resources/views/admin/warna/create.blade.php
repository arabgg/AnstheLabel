@extends('admin.layouts.app')

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">Tambah Warna</h1>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-2 mb-4 rounded">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('warna.store') }}" method="POST">
            @csrf
            {{-- Input warna --}}
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Pilih Warna</label>
                <div class="flex gap-2 items-center">
                    {{-- Color Picker (tanpa name) --}}
                    <input type="color" value="{{ old('kode_hex', '#000000') }}" class="w-12 h-10 p-0 border rounded"
                        id="colorPicker">
                    {{-- Input HEX manual (ini yang dikirim ke database) --}}
                    <input type="text" name="kode_hex" value="{{ old('kode_hex', '#000000') }}"
                        class="border px-3 py-2 rounded w-32" placeholder="#000000" maxlength="7" id="hexInput">
                </div>
                <small class="text-gray-500">Gunakan format HEX (contoh: #ff0000)</small>
            </div>
            
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Nama Warna</label>
                <input type="text" name="nama_warna" class="w-full border px-3 py-2 rounded"
                    value="{{ old('nama_warna') }}">
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan</button>
            <a href="{{ route('ukuran.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Kembali</a>
        </form>
    </div>

    {{-- Script untuk sinkronisasi color picker dan input text --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const colorPicker = document.getElementById('colorPicker');
            const hexInput = document.getElementById('hexInput');

            colorPicker.addEventListener("input", () => {
                hexInput.value = colorPicker.value;
            });

            hexInput.addEventListener("input", () => {
                if (/^#([0-9A-Fa-f]{6})$/.test(hexInput.value)) {
                    colorPicker.value = hexInput.value;
                }
            });
        });
    </script>
@endsection
