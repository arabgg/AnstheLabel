@extends('layouts.app')

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('home') }}">Home</a> / 
        <a href="{{ route('collection') }}">Collection</a> / 
        <span>{{ $produk->nama_produk }}</span>
    </div>
@endsection

@section('content')
    <div class="detail-product-page">
        <div class="detail-container">
            <div class="detail-product-wrapper">
                <!-- Left: Product Images -->
                <div class="detail-product-images">
                    {{-- Foto Utama --}}
                    <img class="detail-main-image"
                        src="{{ asset('storage/foto_produk/' . $produk->fotoUtama->foto_produk) }}"
                        alt="{{ $produk->nama_produk }}">

                    {{-- Foto Thumbnail --}}
                    <div class="detail-thumbnail-wrapper">
                        @foreach ($produk->foto->where('status_foto', 0) as $foto)
                            <img src="{{ asset('storage/foto_produk/' . $foto->foto_produk) }}"
                                alt="Thumbnail {{ $loop->iteration }}">
                        @endforeach
                    </div>
                </div>

                <!-- Right: Product Info -->
                <div class="detail-product-info">
                    <div class="detail-section">
                        <h2 class="detail-product-name">{{ $produk->nama_produk }}</h2>
                        <p class="detail-product-kategori">{{ $produk->deskripsi }}</p>
                    </div>
                    
                    <div class="detail-section-info">
                        <div class="detail-color-wrapper">
                            <p>Colors</p>
                            @if ($produk->warnaProduk->isNotEmpty())
                                <div class="bestproduk-color-dots">
                                    @foreach ($produk->warnaProduk as $warnaItem)
                                        @if ($warnaItem->warna)
                                            <span class="bestproduk-dot"
                                                style="background-color: {{ $warnaItem->warna->kode_hex ?? '#000000' }};">
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="produk-ukuran-wrapper">
                            <p class="mb-2 font-semibold">Pilih Ukuran:</p>
                            <div class="flex gap-2 flex-wrap">
                                @foreach ($produk->ukuran as $ukuranItem)
                                    @if ($ukuranItem->ukuran)
                                        @php
                                            $isActive = isset($defaultUkuran) && $defaultUkuran->ukuran_id === $ukuranItem->ukuran_id;
                                        @endphp
                                        <button 
                                            type="button" 
                                            class="ukuran-button px-4 py-2 border rounded 
                                                {{ $isActive ? 'bg-[#560024] text-white' : 'bg-gray-200 text-black' }}"
                                            data-deskripsi="{{ $ukuranItem->ukuran->deskripsi ?? 'Tidak ada deskripsi.' }}"
                                            onclick="updateDeskripsiUkuran(this)">
                                            {{ $ukuranItem->ukuran->nama_ukuran }}
                                        </button>
                                    @endif
                                @endforeach
                            </div>

                            <div id="deskripsi-ukuran" class="mt-4 p-3 bg-gray-100 rounded text-sm text-gray-800">
                                {{ $defaultUkuran->ukuran->deskripsi ?? 'Silakan pilih ukuran.' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="detail-recommend">
        <h2>You May Also Like</h2>
        <div class="detail-recommend-grid">
            @foreach ($rekomendasi as $item)
            <div class="detail-recommend-card">
                <a href="{{ route('detail.show', $item->produk_id) }}">
                    <img src="{{ asset('storage/foto_produk/' . $item->fotoUtama->foto_produk) }}" alt="{{ $item->nama_produk }}">
                    <h3>{{ $item->nama_produk }}</h3>
                    <p>{{ $item->kategori->nama_kategori }}</p>
                </a>
            </div>
            @endforeach
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function updateDeskripsiUkuran(button) {
        const deskripsi = button.getAttribute('data-deskripsi');
        document.getElementById('deskripsi-ukuran').innerText = deskripsi;

        // Update styling tombol
        const buttons = document.querySelectorAll('.ukuran-button');
        buttons.forEach(btn => {
            btn.classList.remove('bg-[#560024]', 'text-white');
            btn.classList.add('bg-gray-200', 'text-black');
        });

        button.classList.remove('bg-gray-200', 'text-black');
        button.classList.add('bg-[#560024]', 'text-white');
    }
</script>

@endpush