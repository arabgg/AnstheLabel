@extends('layouts.app')

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('page') }}">Home</a> / 
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
                                <div class="detail-color-dot">
                                    @foreach ($produk->warnaProduk as $warnaItem)
                                        @if ($warnaItem->warna)
                                            <span class="detail-dot"
                                                style="background-color: {{ $warnaItem->warna->kode_hex ?? '#000000' }};">
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="detail-size-wrapper">    
                            <p>Size Guide</p>
                            @if ($produk->ukuran->isNotEmpty())
                                <div class="detail-size">
                                    @foreach ($produk->ukuran as $sizeItem)
                                        @if ($sizeItem->produk)
                                            <span class="detail-size-nama">
                                                {{ $sizeItem->ukuran->nama_ukuran }}
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="detail-toko-wrapper">    
                            <p>Link Shop</p>
                            @if ($produk->toko->isNotEmpty())
                                <div class="detail-toko">
                                    @foreach ($produk->toko as $tokoItem)
                                        @if ($tokoItem->produk)
                                        <a href="{{ $tokoItem->url_toko }}">
                                            <img src="{{ asset('storage/icon/' . $tokoItem->toko->icon_toko) }}" 
                                            alt="{{ $tokoItem->toko->nama_toko }}">
                                        </a>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
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
@endpush