@extends('admin.layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto px-4 py-8">
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
                <div class="flex items-center gap-3">
                    <h1 class="text-3xl font-bold text-gray-800">{{ $produk->nama_produk }}</h1>
                    @if ($produk->is_best)
                        <span class="px-3 py-1 bg-pink-600 text-white text-xs rounded-full">Best Seller</span>
                    @endif
                </div>

                {{-- Kode Produk (jika ada) --}}
                @if (!empty($produk->kode_produk))
                    <p class="text-sm text-gray-500">Kode Produk: {{ $produk->kode_produk }}</p>
                @endif

                {{-- Harga --}}
                @php
                    $hargaAkhir = $produk->diskon > 0 ? $produk->harga - $produk->diskon : $produk->harga;
                @endphp

                <div>
                    @if ($produk->diskon > 0)
                        <div class="text-lg text-gray-500 line-through">
                            Rp {{ number_format($produk->harga, 0, ',', '.') }}
                        </div>
                        <div class="text-xl text-amber-600 font-bold">
                            Rp {{ number_format($hargaAkhir, 0, ',', '.') }}
                        </div>
                        <div class="text-sm text-green-600 font-medium">
                            Diskon: Rp {{ number_format($produk->diskon, 0, ',', '.') }}
                        </div>
                    @else
                        <div class="text-xl text-amber-600 font-bold">
                            Rp {{ number_format($hargaAkhir, 0, ',', '.') }}
                        </div>
                    @endif
                </div>

                {{-- Stok --}}
                <div>
                    <label class="font-semibold">Stok:</label>
                    <p class="text-gray-700">{{ $produk->stok_produk }} pcs</p>
                </div>

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
                    <label class="font-semibold">Kategori:</label>
                    <p class="text-gray-700">{{ $produk->kategori->nama_kategori ?? '-' }}</p>
                </div>

                {{-- Bahan --}}
                <div>
                    <label class="font-semibold">Bahan:</label>
                    <p class="text-gray-700">{{ $produk->bahan->nama_bahan ?? '-' }}</p>
                    <p class="text-sm text-gray-500">{{ $produk->bahan->deskripsi ?? '' }}</p>
                </div>


                {{-- Deskripsi --}}
                <div>
                    <label class="font-semibold">Deskripsi:</label>
                    <p class="text-gray-700 leading-relaxed">
                        {{ $produk->deskripsi ?? '-' }}
                    </p>
                </div>

                {{-- Tanggal Dibuat & Diperbarui --}}
                <div class="text-sm text-gray-500">
                    <p>Ditambahkan: {{ $produk->created_at->format('d M Y H:i') }}</p>
                    <p>Terakhir diperbarui: {{ $produk->updated_at->format('d M Y H:i') }}</p>
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
