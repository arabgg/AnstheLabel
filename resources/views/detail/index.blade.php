@extends('layouts.app')

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('page') }}">Home</a> / 
        <a href="{{ route('collection') }}">Collection</a> / 
        <span>{{ $show->produk->nama_produk }}</span>
    </div>
@endsection

@section('content')
    <div class="detail-product-page">
        <div class="detail-container">
            <div class="detail-product-wrapper">
                <!-- Left: Product Images -->
                <div class="detail-product-images">
                    <img class="detail-main-image" src="{{ asset('storage/foto_produk/foto_hijab1.jpg') }}" alt="Rumila Scarf">
                    <div class="detail-thumbnail-wrapper">
                        <img src="{{ asset('storage/foto_produk/foto_hijab1.jpg') }}" alt="Thumb 1">
                        <img src="{{ asset('storage/foto_produk/foto_hijab1.jpg') }}" alt="Thumb 2">
                        <img src="{{ asset('storage/foto_produk/foto_hijab1.jpg') }}" alt="Thumb 3">
                        <img src="{{ asset('storage/foto_produk/foto_hijab1.jpg') }}" alt="Thumb 4">
                        <img src="{{ asset('storage/foto_produk/foto_hijab1.jpg') }}" alt="Thumb 5">
                    </div>
                </div>

                <!-- Right: Product Info -->
                <div class="detail-product-info">
                    <h2 class="detail-product-name">Rumila Scarf</h2>
                    <p class="detail-product-price">Rp 198.000</p>

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
@endsection

@push('scripts')
@endpush