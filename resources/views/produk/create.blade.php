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

    <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data"
        class="bg-white p-6 rounded shadow-md space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Kolom Kiri (Foto) --}}
            <div class="space-y-6">
                {{-- Foto Utama --}}
                <div>
                    <label class="block font-medium mb-1">Foto Utama</label>
                    <input type="file" name="foto_utama" id="foto-utama" accept="image/*"
                        class="border border-gray-300 rounded px-3 py-2 w-full" required>
                    <div class="mt-3 w-60 aspect-[4/5] border flex items-center justify-center rounded overflow-hidden">
                        <img id="preview-utama" src="https://via.placeholder.com/200?text=+"
                            class="object-cover w-full h-full" alt="Preview Utama">
                    </div>
                </div>

                {{-- Foto Sekunder --}}
                <div>
                    <label class="block font-medium mb-1">Foto Sekunder (Maksimal 8)</label>
                    <input type="file" name="foto_sekunder[]" accept="image/*" multiple
                        class="border border-gray-300 rounded px-3 py-2 w-full" id="foto-sekunder">
                    <div id="preview-sekunder" class="flex flex-wrap gap-3 mt-3"></div>
                </div>
            </div>

            {{-- Kolom Kanan (Detail Produk) --}}
            <div class="space-y-6">
                {{-- Nama Produk --}}
                <div>
                    <label class="block font-medium mb-1">Nama Produk</label>
                    <input type="text" name="nama_produk" class="border border-gray-300 rounded px-3 py-2 w-full"
                        required>
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label class="block font-medium mb-1">Deskripsi</label>
                    <textarea name="deskripsi" rows="4" class="border border-gray-300 rounded px-3 py-2 w-full" required></textarea>
                </div>
                <div>
                    <label class="block font-medium mb-1">Harga</label>
                    <textarea name="harga" rows="4" class="border border-gray-300 rounded px-3 py-2 w-full" required></textarea>
                </div>

                {{-- Kategori --}}
                <div>
                    <label class="block font-medium mb-1">Kategori</label>
                    <select name="kategori_id" class="border border-gray-300 rounded px-3 py-2 w-full" required>
                        <option value="">Pilih Kategori</option>
                        @foreach ($kategori as $k)
                            <option value="{{ $k->kategori_id }}">{{ $k->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Bahan --}}
                <div>
                    <label class="block font-medium mb-1">Bahan</label>
                    <select name="bahan_id" class="border border-gray-300 rounded px-3 py-2 w-full" required>
                        <option value="">Pilih Bahan</option>
                        @foreach ($bahan as $b)
                            <option value="{{ $b->bahan_id }}">{{ $b->nama_bahan }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Ukuran --}}
                <div>
                    <label class="block font-medium mb-1">Ukuran</label>
                    <div class="flex flex-wrap gap-3">
                        @foreach ($ukuran as $itemUkuran)
                            <label class="flex items-center gap-2 border px-3 py-1 rounded cursor-pointer">
                                <input type="checkbox" name="ukuran_id[]" value="{{ $itemUkuran->ukuran_id }}">
                                {{ $itemUkuran->nama_ukuran }}
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Warna --}}
                <div>
                    <label class="block font-medium mb-1">Warna</label>
                    <div class="flex flex-wrap gap-3">
                        @foreach ($warna as $itemWarna)
                            <label class="flex items-center gap-2 border px-3 py-1 rounded cursor-pointer">
                                <input type="checkbox" name="warna_id[]" value="{{ $itemWarna->warna_id }}">
                                <span class="w-6 h-6 rounded"
                                    style="background-color: {{ $itemWarna->kode_hex }}"></span>
                                <span>{{ $itemWarna->kode_hex }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>

        <div class="pt-4">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
        </div>
    </form>
</div>

{{-- Preview Foto --}}
<script>
    // Preview Foto Utama
    document.getElementById('foto-utama').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        const preview = document.getElementById('preview-utama');
        preview.src = URL.createObjectURL(file);
    });

    // Preview Foto Sekunder
    document.getElementById('foto-sekunder').addEventListener('change', function(e) {
        const previewContainer = document.getElementById('preview-sekunder');
        previewContainer.innerHTML = '';
        const files = Array.from(e.target.files).slice(0, 8);

        files.forEach(file => {
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.classList.add('w-20', 'h-20', 'object-cover', 'rounded');
            previewContainer.appendChild(img);
        });
    });
</script>
@endsection
