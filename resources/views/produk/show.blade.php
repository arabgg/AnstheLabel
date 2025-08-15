@extends('admin.layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="grid md:grid-cols-2 gap-10 items-start">
            {{-- Bagian Kiri: Gambar Utama --}}
            <div>
                @if ($produk->fotoUtama)
                    <img src="{{ asset('storage/foto_produk/' . $produk->fotoUtama->foto_produk) }}"
                        alt="{{ $produk->nama_produk }}" class="w-full aspect-[4/5] object-cover rounded-md shadow">
                @else
                    <img src="{{ asset('images/default.jpg') }}" alt="Default Image"
                        class="w-full aspect-[4/5] object-cover rounded-md shadow">
                @endif

                {{-- Thumbnail Foto Tambahan --}}
                <div class="grid grid-cols-2 gap-3 mt-4">
                    @foreach ($produk->foto as $foto)
                        <img src="{{ asset('storage/foto_produk/' . $foto->foto_produk) }}" alt="Thumbnail"
                            class="w-full aspect-[4/5] object-cover rounded">
                    @endforeach
                </div>
            </div>

            {{-- Bagian Kanan: Detail Produk --}}
            <div class="space-y-6">
                {{-- Nama Produk --}}
                <h1 class="text-3xl font-bold text-gray-800">{{ $produk->nama_produk }}</h1>

                {{-- Harga --}}
                @php
                    $hargaAkhir = $produk->diskon > 0 ? $produk->harga - $produk->diskon : $produk->harga;
                @endphp

                @if ($produk->diskon > 0)
                    {{-- Harga asli dicoret (lebih besar) --}}
                    <div class="text-lg text-gray-500 line-through">
                        Rp {{ number_format($produk->harga, 0, ',', '.') }}
                    </div>

                    {{-- Harga setelah diskon (lebih kecil) --}}
                    <div class="text-base text-amber-600 font-semibold">
                        Rp {{ number_format($hargaAkhir, 0, ',', '.') }}
                    </div>
                @else
                    {{-- Harga normal --}}
                    <div class="text-lg text-amber-600 font-semibold">
                        Rp {{ number_format($hargaAkhir, 0, ',', '.') }}
                    </div>
                @endif

                {{-- Warna --}}
                @if ($produk->warna && $produk->warna->count())
                    <div>
                        <h3 class="text-sm font-semibold mb-1">Warna Tersedia:</h3>
                        <div class="flex gap-2">
                            @foreach ($produk->warna as $wp)
                                @if ($wp->warna)
                                    <div class="w-6 h-6 rounded-full border"
                                        style="background-color: {{ $wp->warna->kode_hex }};"
                                        title="{{ $wp->warna->nama_warna }}">
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif


                {{-- Ukuran --}}
                @if ($produk->ukuran && $produk->ukuran->count())
                    <div>
                        <h3 class="text-sm font-semibold mb-1">Ukuran Tersedia:</h3>
                        <div class="flex gap-2 flex-wrap">
                            @foreach ($produk->ukuran as $up)
                                @if ($up->ukuran)
                                    <span class="px-3 py-1 border rounded text-sm">
                                        {{ $up->ukuran->nama_ukuran }}
                                    </span>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif


                {{-- Kategori --}}
                <div>
                    <label for="kategori" class="font-semibold">Kategori:</label>
                    <p class="text-gray-700 leading-relaxed">{{ $produk->kategori->nama_kategori ?? '-' }}</p>
                </div>

                {{-- Bahan --}}
                <div>
                    <label for="bahan" class="font-semibold">Bahan:</label>
                    <p class="text-gray-700 leading-relaxed">{{ $produk->bahan->nama_bahan ?? '-' }}</p>
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label for="deskripsi" class="font-semibold">Deskripsi:</label>
                    <p class="text-gray-700 leading-relaxed">
                        {{ $produk->deskripsi ?? '-' }}
                    </p>
                </div>

                {{-- Tombol Kembali --}}
                <a href="{{ url('/produk') }}"
                    class="inline-block px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-700 transition">
                    ← Kembali
                </a>
            </div>
        </div>
    </div>
@endsection
