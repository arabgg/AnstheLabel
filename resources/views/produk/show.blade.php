@extends('admin.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="grid md:grid-cols-2 gap-10 items-start">
        {{-- Bagian Kiri: Gambar Utama --}}
        <div>
            @if ($produk->fotoUtama)
                <img src="{{ asset('storage/foto_produk/' . $produk->fotoUtama->foto_produk) }}"
                    alt="{{ $produk->nama_produk }}"
                    class="w-full h-[500px] object-cover rounded-md shadow">
            @else
                <img src="{{ asset('images/default.jpg') }}"
                    alt="Default Image"
                    class="w-full h-[500px] aspect object-cover rounded-md shadow">
            @endif

            {{-- Thumbnail Foto Tambahan --}}
            <div class="grid grid-cols-3 gap-3 mt-4">
                @foreach ($produk->foto as $foto)
                    <img src="{{ asset('storage/foto_produk/' . $foto->foto_produk) }}"
                        alt="Thumbnail"
                        class="w-full h-32 object-cover rounded">
                @endforeach
            </div>
        </div>

        {{-- Bagian Kanan: Detail Produk --}}
        <div class="space-y-6">
            <h1 class="text-3xl font-bold text-gray-800">{{ $produk->nama_produk }}</h1>
            <div class="text-xl text-amber-600 font-semibold">
                Rp {{ number_format($produk->harga, 0, ',', '.') }}
            </div>

            {{-- Warna --}}
            @if ($produk->warna && $produk->warna->count())
                <div>
                    <h3 class="text-sm font-semibold mb-1">Warna Tersedia:</h3>
                    <div class="flex gap-2">
                        @foreach ($produk->warna as $warna)
                            <div class="w-6 h-6 rounded-full border"
                                 style="background-color: {{ $warna->kode_hex }};"
                                 title="{{ $warna->nama_warna }}">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Ukuran --}}
            @if ($produk->ukuran && $produk->ukuran->count())
                <div>
                    <h3 class="text-sm font-semibold mb-1">Ukuran Tersedia:</h3>
                    <div class="flex gap-2 flex-wrap">
                        @foreach ($produk->ukuran as $ukuran)
                            <span class="px-3 py-1 border rounded text-sm">{{ $ukuran->nama_ukuran }}</span>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Deskripsi --}}
            <div>
                <h2 class="text-lg font-semibold">Deskripsi</h2>
                <p class="text-gray-700 leading-relaxed">{{ $produk->deskripsi ?? '-' }}</p>
            </div>

            {{-- Bahan dan Kategori --}}
            <div class="text-sm text-gray-600 space-y-1">
                <p><strong>Kategori:</strong> {{ $produk->kategori->nama_kategori ?? '-' }}</p>
                <p><strong>Bahan:</strong> {{ $produk->bahan->nama_bahan ?? '-' }}</p>
            </div>

            {{-- Tombol --}}
            <a href="{{ url('/produk') }}"
               class="inline-block px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-700 transition">
                ← Kembali
            </a>
        </div>
    </div>
</div>
@endsection
