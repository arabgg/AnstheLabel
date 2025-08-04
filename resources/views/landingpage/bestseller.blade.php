<div class="bestproduk-section">
    <div class="bestproduk-header">
        <h1 class="bestproduk-title">BEST SELLER</h1>
        
        <div class="bestproduk-buttons">
            <a href="{{ route('collection') }}" class="view-all-link">VIEW ALL</a>  
        </div>
    </div>

    <div class="bestproduk-grid" id="katalogGrid">
        @foreach ($bestproduk as $index => $item)
            <div class="bestproduk-card">
                <div class="bestproduk-image-wrapper">
                    <img src="{{ asset('storage/images/bestproduk/' . $item['image']) }}" alt="{{ $item['nama'] }}" class="bestproduk-image default-image">
                    @if (!empty($item['image_hover']))
                        <img src="{{ asset('storage/images/bestproduk/' . $item['image_hover']) }}" alt="{{ $item['nama'] }}" class="bestproduk-image hover-image">
                    @endif
                </div>
                
                <div class="bestproduk-info">
                    <div class="bestproduk-name">{{ $item['nama'] }}</div>
                    <div class="bestproduk-price">
                        @if (!empty($item['harga_diskon']))
                            <span class="price-discounted">{{ $item['harga'] }}</span>
                            <span class="price-now">{{ $item['harga_diskon'] }}</span>
                        @else
                            <span class="price-now">{{ $item['harga'] }}</span>
                        @endif
                    </div>
                    <div class="bestproduk-kategori">{{ $item['kategori'] }}</div>
                </div>
            </div>
        @endforeach
    </div>
</div>
