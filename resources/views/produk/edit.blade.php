@extends('admin.layouts.app')

@section('content')
<div class="p-6 bg-white rounded shadow-md max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Edit Produk</h1>

    <form action="/produk/{{ $produk->produk_id }}/update" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-gray-700 font-semibold mb-1">Nama Produk</label>
            <input type="text" name="nama_produk" value="{{ $produk->nama_produk }}" required
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-gray-700 font-semibold mb-1">Deskripsi</label>
            <textarea name="deskripsi" required rows="4"
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $produk->deskripsi }}</textarea>
        </div>

        <div>
            <label class="block text-gray-700 font-semibold mb-1">Kategori</label>
            <select name="kategori_id"
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                @foreach ($kategori as $k)
                    <option value="{{ $k->kategori_id }}" {{ $produk->kategori_id == $k->kategori_id ? 'selected' : '' }}>
                        {{ $k->nama_kategori }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-gray-700 font-semibold mb-1">Bahan</label>
            <select name="bahan_id"
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                @foreach ($bahan as $b)
                    <option value="{{ $b->bahan_id }}" {{ $produk->bahan_id == $b->bahan_id ? 'selected' : '' }}>
                        {{ $b->nama_bahan }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex justify-end">
            <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-200">
                Perbarui Produk
            </button>
        </div>
    </form>
</div>
@endsection
