@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">Detail Bahan</h1>

    <div class="bg-white shadow rounded p-4">
        <p><strong>ID:</strong> {{ $bahan->bahan_id }}</p>
        <p><strong>Nama Bahan:</strong> {{ $bahan->nama_bahan }}</p>
        <p><strong>Deskripsi:</strong> {{ $bahan->deskripsi }}</p>
    </div>

    <div class="mt-4">
        <a href="{{ route('bahan.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Kembali</a>
        <a href="{{ route('bahan.edit', $bahan->bahan_id) }}" class="bg-green-500 text-white px-4 py-2 rounded">Edit</a>
    </div>
</div>
@endsection
