@extends('admin.layouts.app')

@section('content')
    <div class="p-2">
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-600 rounded-xl">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="produk-form" action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data"
            class="bg-white p-6 rounded-xl shadow-md space-y-6">
            @csrf

            {{-- Grid 2 Kolom --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                {{-- Bagian Kiri --}}
                <div class="space-y-6">
                    {{-- Nama Produk --}}
                    <div>
                        <label class="block font-medium mb-1">Nama Produk</label>
                        <input type="text" name="nama_produk" class="border border-gray-300 rounded-xl px-3 py-2 w-full"
                            required>
                    </div>

                    {{-- Status Best --}}
                    <div class="mt-3">
                        <input type="hidden" name="is_best" value="0">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_best" value="1"
                                {{ old('is_best', $produk->is_best ?? 0) == 1 ? 'checked' : '' }}
                                class="rounded border-gray-300 text-pink-600 focus:ring-pink-500">
                            <span class="ml-2 text-sm text-gray-700">Tandai sebagai produk terbaik</span>
                        </label>
                    </div>

                    {{-- Stok --}}
                    <div>
                        <label class="block font-medium mb-1">Stok</label>
                        <input type="number" name="stok_produk" class="border border-gray-300 rounded-xl px-3 py-2 w-full"
                            required>
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <label class="block font-medium mb-1">Deskripsi</label>
                        <textarea name="deskripsi" rows="4" class="border border-gray-300 rounded-xl px-3 py-2 w-full" required></textarea>
                    </div>

                    {{-- Harga & Diskon --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-medium mb-1">Harga</label>
                            <input type="number" step="0.01" name="harga"
                                class="border border-gray-300 rounded-xl px-3 py-2 w-full" required>
                        </div>
                        <div>
                            <label class="block font-medium mb-1">Diskon</label>
                            <input type="number" step="0.01" name="diskon"
                                class="border border-gray-300 rounded-xl px-3 py-2 w-full">
                        </div>
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
                                    <span class="w-6 h-6 rounded-xl" style="background-color: {{ $itemWarna->kode_hex }}"
                                        title="{{ $itemWarna->kode_hex }}"></span>
                                    <span>{{ $itemWarna->nama_warna }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Bagian Kanan --}}
                <div class="space-y-6">
                    {{-- Foto Utama --}}
                    <div>
                        <label class="block font-medium mb-1">Foto Utama</label>
                        <input type="file" name="foto_utama" id="foto-utama" accept="image/*"
                            class="border border-gray-300 rounded-xl px-3 py-2 w-full" required>
                        <div
                            class="mt-3 w-60 aspect-[4/5] border flex items-center justify-center rounded-xl overflow-hidden bg-gray-100">
                            <img id="preview-utama" class="object-cover w-full h-full hidden" alt="Preview Utama">
                            <svg id="placeholder-icon-utama" class="w-1/3 h-1/3 text-gray-400" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-4 4 4 4-4v4zM6 9a1 1 0 11-2 0 1 1 0 012 0zm2 0a1 1 0 11-2 0 1 1 0 012 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>

                    {{-- Foto Sekunder --}}
                    <div>
                        <label class="block font-medium mb-2">Foto Sekunder (Maksimal 8)</label>
                        <div class="grid grid-cols-2 gap-4">
                            @for ($i = 1; $i <= 8; $i++)
                                <div>
                                    <label class="text-sm text-gray-600 block mb-1">Foto {{ $i + 1 }}</label>
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

            {{-- Tombol Aksi --}}
            <div class="pt-6 flex justify-between items-center gap-4">
                <a href="{{ url('/produk') }}"
                    class="flex-[1] text-center px-4 py-4 bg-gray-200 text-gray-800 rounded-xl font-medium hover:bg-gray-300 transition duration-300 shadow-sm">
                    Batal
                </a>
                <button type="submit"
                    class="flex-[2] text-center py-4 bg-[#560024] text-white rounded-xl font-medium hover:bg-[#7a0033] transition duration-300 shadow-sm">
                    Simpan
                </button>
            </div>
        </form>
    </div>
@endsection

---

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const fotoUtamaInput = document.getElementById('foto-utama');
        const previewUtama = document.getElementById('preview-utama');
        const placeholderIconUtama = document.getElementById('placeholder-icon-utama');

        // Initial state: hide image, show icon
        previewUtama.classList.add('hidden');
        placeholderIconUtama.classList.remove('hidden');

        // Preview Foto Utama
        fotoUtamaInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                previewUtama.src = URL.createObjectURL(file);
                previewUtama.classList.remove('hidden'); // Show the image
                placeholderIconUtama.classList.add('hidden'); // Hide the icon
            } else {
                previewUtama.src = '';
                previewUtama.classList.add('hidden'); // Hide the image
                placeholderIconUtama.classList.remove('hidden'); // Show the icon
            }
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
    });
</script>
