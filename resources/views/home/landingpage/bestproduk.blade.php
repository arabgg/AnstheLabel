<div class="swiper arrival-swiper">
    <h1 class="swiper-title">Best Product</h1>
    <div class="swiper-wrapper">
        @foreach ($bestproduk as $bestproduk)
            <div class="swiper-slide">
                <!-- Skeleton -->
                <div class="skeleton-wrapper">
                    <div class="skeleton skeleton-img"></div>
                </div>

                <!-- Konten utama -->
                <div class="skeleton-target" style="display:none;">
                    <img src="{{ route('storage', ['folder' => 'foto_produk', 'filename' => $bestproduk->fotoUtama->foto_produk]) }}" alt="{{ $bestproduk->nama_produk }}">
                    <div class="arrival-caption">
                        <h3>{{ $bestproduk->nama_produk }}</h3>
                        <a href="{{ route('collection') }}">
                            <button>Shop here</button>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>