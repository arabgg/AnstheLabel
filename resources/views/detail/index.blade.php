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
                        <p class="detail-product-kategori">{{ $produk->kategori->nama_kategori }}</p>
                    </div>
                    
                    <div class="detail-section-info">
                        <div class="detail-color-wrapper">
                            <p>Color Available</p>
                            @if ($produk->warna->isNotEmpty())
                                <div class="detail-color-dot">
                                    @foreach ($produk->warna as $warnaItem)
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
                            <p>Size Available</p>
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
                            <div class="detail-buy-header">BUY NOW</div>

                            @if ($produk->toko->isNotEmpty())
                                @foreach ($produk->toko as $tokoItem)
                                    <a href="{{ $tokoItem->url_toko }}" class="detail-toko" target="_blank">
                                        @if (!empty($tokoItem->toko->icon_toko))
                                            <img src="{{ asset('storage/icon/' . $tokoItem->toko->icon_toko) }}" alt="{{ $tokoItem->toko->nama_toko }}">
                                        @endif
                                        {{ $tokoItem->toko->nama_toko }}
                                    </a>
                                @endforeach
                            @else
                                <div class="detail-toko">Toko tidak tersedia</div>
                            @endif
                        </div>
                    </div>

                    <div class="detail-deskripsi-wrapper">
                        <h3>Detail Produk</h3>
                        <div class="detail-deskripsi-produk">
                            {{-- <h3>Deskripsi Produk</h3> --}}
                            <p>{{ $produk->deskripsi }}</p>
                        </div>

                        @if ($produk->ukuran->isNotEmpty())
                            <div class="detail-deskripsi-bahan">
                                <h5>Ukuran yang tersedia pada produk</h5>
                                @foreach ($produk->ukuran as $sizeItem)
                                    @if ($sizeItem->produk)
                                        <p>{{ $sizeItem->ukuran->nama_ukuran }} - {{ $sizeItem->ukuran->deskripsi }}</p>
                                    @endif
                                @endforeach
                            </div>
                        @endif

                        <div class="detail-deskripsi-ukuran">
                            <h5>Penjelasan Bahan</h5>
                            <h6>Jenis Bahan : {{ $produk->bahan->nama_bahan }}</h6>
                            <p>{{ $produk->bahan->deskripsi }}</p>
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