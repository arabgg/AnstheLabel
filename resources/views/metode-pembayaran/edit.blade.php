@extends('admin.layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Edit Metode Pembayaran</h1>

    <form action="{{ route('metode-pembayaran.update', $metode->metode_pembayaran_id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block font-medium">Nama Pembayaran</label>
            <input type="text" name="nama_pembayaran" value="{{ old('nama_pembayaran', $metode->nama_pembayaran) }}" class="w-full border rounded px-3 py-2">
            @error('nama_pembayaran') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block font-medium">Kode Bayar</label>
            <input type="text" name="kode_bayar" value="{{ old('kode_bayar', $metode->kode_bayar) }}" class="w-full border rounded px-3 py-2">
            @error('kode_bayar') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block font-medium">Status</label>
            <select name="status_pembayaran" class="w-full border rounded px-3 py-2">
                <option value="1" {{ old('status_pembayaran', $metode->status_pembayaran) == 1 ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ old('status_pembayaran', $metode->status_pembayaran) == 0 ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>

        <div>
            <label class="block font-medium">Icon (URL)</label>
            <input type="text" name="icon" value="{{ old('icon', $metode->icon) }}" class="w-full border rounded px-3 py-2">
        </div>

        <div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded shadow hover:bg-blue-600">Update</button>
            <a href="{{ route('metode-pembayaran.index') }}" class="ml-2 text-gray-600">Batal</a>
        </div>
    </form>
</div>
@endsection
