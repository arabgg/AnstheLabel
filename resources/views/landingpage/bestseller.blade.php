<div class="bestseller-section">
    <div class="bestseller-header">
        <h1 class="bestseller-title">Best Seller</h1>
    </div>

    <div class="bestseller-grid" id="katalogGrid">
        @foreach ($bestproduk as $index => $item)
            <div class="bestseller-card">
                <div class="bestseller-image-wrapper">
                    <img src="{{ asset('storage/images/bestproduk/' . $item['image']) }}" alt="{{ $item['nama'] }}" class="bestseller-image default-image">
                    @if (!empty($item['image_hover']))
                        <img src="{{ asset('storage/images/bestproduk/' . $item['image_hover']) }}" alt="{{ $item['nama'] }}" class="bestseller-image hover-image">
                    @endif
                </div>
                
                <div class="bestseller-info">
                    <div class="bestseller-name">{{ $item['nama'] }}</div>
                    <div class="bestseller-price">
                        @if (!empty($item['harga_diskon']))
                            <span class="price-discounted">{{ $item['harga'] }}</span>
                            <span class="price-now">{{ $item['harga_diskon'] }}</span>
                        @else
                            <span class="price-now">{{ $item['harga'] }}</span>
                        @endif
                    </div>
                    <div class="bestseller-kategori">{{ $item['kategori'] }}</div>
                </div>
            </div>
        @endforeach
    </div>
</div>
