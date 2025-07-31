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
                    
                    <div class="detail-section">
                        <form>
                            <label>Color: <b>Rumila</b></label>
                            <div class="detail-size-wrapper">
                                <label for="size">Size:</label>
                                <select id="size" name="size">
                                    <option>One Size</option>
                                </select>
                            </div>

                            <div class="detail-quantity-wrapper">
                                <label>Quantity:</label>
                                <input type="number" value="1" min="1">
                            </div>

                            <button type="button" class="detail-btn-soldout">Sold Out</button>
                            <button type="button" class="detail-btn-wishlist">Add to Wishlist</button>
                        </form>

                        <div class="detail-product-description">
                            <p>
                                Dapatkan tampilan memukau dengan Rumila Scarf hijab printed yang
                                didesain dengan kesan kuat dan tegas yang mudah untuk menarik
                                perhatian mulai langsung dari motif sampai rona pada bagian tepi.
                            </p>
                            <ul>
                                <li>Bahan: Voal</li>
                                <li>Size: 115x115 cm</li>
                                <li>*Terdapat sedikit perbedaan warna akibat cahaya/screen</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
@endpush