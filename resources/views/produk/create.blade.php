@extends('admin.layouts.app')

@section('content')
    <div class="flex bg-[#560024] py-4 justify-center mb-4 rounded-xl">
        <h1 class="text-2xl font-bold font-montserrat text-white">Tambah Produk</h1>
    </div>
    <div class="max-w-6xl mx-auto px-4 py-6">
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-600 rounded-xl">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data"
            class="bg-white p-6 rounded-xl shadow-md space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Kolom Kiri --}}

                <div class="space-y-6">
                    {{-- Nama Produk --}}
                    <div>
                        <label class="block font-medium mb-1">Nama Produk</label>
                        <input type="text" name="nama_produk" class="border border-gray-300 rounded-xl px-3 py-2 w-full"
                            required>
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <label class="block font-medium mb-1">Deskripsi</label>
                        <textarea name="deskripsi" rows="4" class="border border-gray-300 rounded-xl px-3 py-2 w-full" required></textarea>
                    </div>

                    {{-- Harga --}}
                    <div>
                        <label class="block font-medium mb-1">Harga</label>
                        <input type="text" name="harga" class="border border-gray-300 rounded-xl px-3 py-2 w-full"
                            required>
                    </div>

                    {{-- Diskon --}}
                    <div>
                        <label class="block font-medium mb-1">Diskon</label>
                        <input type="text" name="diskon" class="border border-gray-300 rounded-xl px-3 py-2 w-full">
                    </div>

                    {{-- Kategori --}}
                    <div>
                        <label class="block font-medium mb-1">Kategori</label>
                        <select name="kategori_id" class="border border-gray-300 rounded-xl px-3 py-2 w-full" required>
                            <option value="">Pilih Kategori</option>
                            @foreach ($kategori as $k)
                                <option value="{{ $k->kategori_id }}">{{ $k->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Bahan --}}
                    <div>
                        <label class="block font-medium mb-1">Bahan</label>
                        <select name="bahan_id" class="border border-gray-300 rounded-xl px-3 py-2 w-full" required>
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
                                <label class="flex items-center gap-2 border px-3 py-1 rounded-xl cursor-pointer">
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
                                <label class="flex items-center gap-2 border px-3 py-1 rounded-xl cursor-pointer">
                                    <input type="checkbox" name="warna_id[]" value="{{ $itemWarna->warna_id }}">
                                    <span class="w-6 h-6 rounded-xl"
                                        style="background-color: {{ $itemWarna->kode_hex }}"></span>
                                    <span>{{ $itemWarna->kode_hex }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan --}}
                <div class="space-y-6">
                    {{-- Foto Utama --}}
                    <div>
                        <label class="block font-medium mb-1">Foto Utama</label>
                        <input type="file" name="foto_utama" id="foto-utama" accept="image/*"
                            class="border border-gray-300 rounded-xl px-3 py-2 w-full" required>
                        <div class="mt-3 w-60 aspect-[4/5] border flex items-center justify-center rounded-xl overflow-hidden">
                            <img id="preview-utama" src="https://via.placeholder.com/200?text=+"
                                class="object-cover w-full h-full" alt="Preview Utama">
                        </div>
                    </div>

                    {{-- Foto Sekunder --}}
                    <div>
                        <label class="block font-medium mb-2">Foto Sekunder (Maksimal 8)</label>
                        <div class="grid grid-cols-2 gap-4">
                            @for ($i = 1; $i <= 8; $i++)
                                <div>
                                    <label class="text-sm text-gray-600 block mb-1">Foto {{ $i }}</label>
                                    <input type="file" name="foto_sekunder[]" accept="image/*"
                                        class="border border-gray-300 rounded-xl px-3 py-2 w-full preview-input"
                                        data-preview="preview-sekunder-{{ $i }}">
                                    <div class="mt-2 flex justify-center">
                                        <img id="preview-sekunder-{{ $i }}"
                                            class="hidden border rounded-xl object-cover" style="width:80px; height:100px;"
                                            alt="Preview">
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>

                </div>
            </div>

            <div class="pt-4 flex justify-between items-center gap-4">
                {{-- Tombol Batal --}}
                <a href="{{ url('/produk') }}"
                    class="flex-[1] text-center px-4 py-4 bg-gray-200 text-gray-800 rounded-xl font-medium hover:bg-gray-300 transition duration-300 shadow-sm">
                    Batal
                </a>

                {{-- Tombol Simpan --}}
                <button type="submit"
                    class="flex-[2] text-center py-4 bg-[#560024] text-white rounded-xl font-medium hover:bg-[#7a0033] transition duration-300 shadow-sm">
                    Simpan
                </button>
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
        document.querySelectorAll('.preview-input').forEach(input => {
            input.addEventListener('change', function(e) {
                const previewId = this.dataset.preview;
                const previewImg = document.getElementById(previewId);

                if (this.files && this.files[0]) {
                    previewImg.src = URL.createObjectURL(this.files[0]);
                    previewImg.classList.remove('hidden');
                } else {
                    previewImg.src = '';
                    previewImg.classList.add('hidden');
                }
            });
        });
    </script>
@endsection
