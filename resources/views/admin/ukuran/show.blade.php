@extends('admin.layouts.app')

@section('content')
<div class="p-6 bg-white rounded shadow-md max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Detail Ukuran</h1>

    <div class="mb-4">
        <label class="block font-semibold mb-1">Nama Ukuran</label>
        <p class="px-4 py-2 border rounded bg-gray-50">{{ $ukuran->nama_ukuran }}</p>
    </div>

    <div class="mb-4">
        <label class="block font-semibold mb-1">Deskripsi</label>
        <p class="px-4 py-2 border rounded bg-gray-50">{{ $ukuran->deskripsi ?? '-' }}</p>
    </div>

    <div class="flex justify-end">
        <a href="{{ route('ukuran.index') }}" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Kembali</a>
    </div>
</div>
@endsection
