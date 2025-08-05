@extends('admin.layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">Tambah Produk</h1>

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 text-red-600 rounded">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ url('/produk/upload') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded shadow-md space-y-6">
        @csrf

        {{-- Foto --}}
        <div>
            <label class="block font-medium mb-1">Foto Utama</label>
            <input type="file" name="foto" accept="image/*" onchange="previewImage(event)" class="block w-full text-sm border rounded px-3 py-2">
            <img id="preview" class="mt-4 max-h-60" src="https://via.placeholder.com/300x300?text=Preview" alt="Preview">
        </div>

        {{-- Nama Produk --}}
        <div>
            <label class="block font-medium mb-1">Nama Produk</label>
            <input type="text" name="nama_produk" required class="block w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-400">
        </div>

        {{-- Deskripsi --}}
        <div>
            <label class="block font-medium mb-1">Deskripsi</label>
            <textarea name="deskripsi" rows="3" required class="block w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-purple-400"></textarea>
        </div>

        {{-- Kategori --}}
        <div>
            <label class="block font-medium mb-1">Kategori</label>
            <select name="kategori_id" class="block w-full border rounded px-3 py-2">
                @foreach ($kategori as $k)
                    <option value="{{ $k->kategori_id }}">{{ $k->nama_kategori }}</option>
                @endforeach
            </select>
        </div>

        {{-- Bahan --}}
        <div>
            <label class="block font-medium mb-1">Bahan</label>
            <select name="bahan_id" class="block w-full border rounded px-3 py-2">
                @foreach ($bahan as $b)
                    <option value="{{ $b->bahan_id }}">{{ $b->nama_bahan }}</option>
                @endforeach
            </select>
        </div>

        {{-- Warna (multiple) --}}
        <div>
            <label class="block font-medium mb-1">Warna Produk</label>
            <div class="flex flex-wrap gap-2">
                <input type="color" name="warna[]" class="w-12 h-12 rounded border">
                <input type="color" name="warna[]" class="w-12 h-12 rounded border">
                <input type="color" name="warna[]" class="w-12 h-12 rounded border">
            </div>
        </div>

        {{-- Ukuran (multiple) --}}
        <div>
            <label class="block font-medium mb-1">Ukuran Produk</label>
            <div class="flex flex-wrap gap-2">
                <input type="text" name="ukuran[]" placeholder="S" class="w-20 border rounded px-2 py-1">
                <input type="text" name="ukuran[]" placeholder="M" class="w-20 border rounded px-2 py-1">
                <input type="text" name="ukuran[]" placeholder="L" class="w-20 border rounded px-2 py-1">
            </div>
        </div>

        {{-- Submit --}}
        <div>
            <button type="submit" class="bg-black text-white px-6 py-2 rounded hover:bg-gray-800 transition duration-200 font-semibold">
                Simpan Produk
            </button>
        </div>
    </form>
</div>

<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function(){
            document.getElementById('preview').src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endsection
