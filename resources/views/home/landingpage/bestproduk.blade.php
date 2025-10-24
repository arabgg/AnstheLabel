<div class="swiper arrival-swiper">
    <h1 class="swiper-title">{{ __('messages.title.best_products') }}</h1>
    <div class="swiper-wrapper">
        @foreach ($bestproduk as $bestproduk)
            <div class="swiper-slide">
                <!-- Skeleton -->
                <div class="skeleton-wrapper">
                    <div class="skeleton skeleton-img"></div>
                </div>

                <!-- Konten utama -->
                <div class="skeleton-target" style="display:none;">
                    @if($bestproduk->fotoUtama && $bestproduk->fotoUtama->foto_produk)
                        <img src="{{ route('storage', ['folder' => 'foto_produk', 'filename' => $bestproduk->fotoUtama->foto_produk]) }}"
                            alt="{{ $bestproduk->nama_produk }}">
                    @else
                        <span>Image not available</span>
                    @endif
                    <div class="arrival-caption">
                        <h3>{{ $bestproduk->nama_produk }}</h3>
                        <a href="{{ route('collection') }}">
                            <button>{{ __('messages.button.shop') }}</button>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>