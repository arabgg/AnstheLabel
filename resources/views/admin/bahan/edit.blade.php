@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">Edit Bahan</h1>

    <form action="{{ route('bahan.update', $bahan->bahan_id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label for="nama_bahan" class="block">Nama Bahan</label>
            <input type="text" name="nama_bahan" id="nama_bahan" value="{{ old('nama_bahan', $bahan->nama_bahan) }}" class="w-full border p-2 rounded" required>
        </div>

        <div>
            <label for="deskripsi" class="block">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" class="w-full border p-2 rounded">{{ old('deskripsi', $bahan->deskripsi) }}</textarea>
        </div>

        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Update</button>
        <a href="{{ route('bahan.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Batal</a>
    </form>
</div>
@endsection
