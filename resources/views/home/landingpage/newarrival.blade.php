<div class="bestproduk-section">
    <div class="bestproduk-header">
        <h1 class="bestproduk-title">New Arrival</h1>
    </div>

    <div class="bestproduk-grid" id="katalogGrid">
        @foreach ($newarrival as $index => $item)
            <div class="bestproduk-card">
                {{-- Skeleton Wrapper --}}
                <div class="skeleton-wrapper">
                    <div class="skeleton skeleton-img"></div>
                </div>

                <div class="skeleton-target" style="display:none;">
                    <a href="{{ route('detail.show', $item->produk_id) }}">
                        <div class="bestproduk-image-wrapper">
                            @if (!empty($item->diskon && $item->diskon > 0))
                                <span class="diskon-label">Save {{ $item->diskon_persen }} %</span>
                            @endif
                            
                            {{-- Gambar utama dengan status_foto = 1 --}}
                            @if ($item->fotoUtama)
                                <img src="{{ route('storage', ['folder' => 'foto_produk', 'filename' => $item->fotoUtama->foto_produk]) }}" class="bestproduk-image default-image">
                            @endif
    
                            @if ($item->hoverFoto)
                                <img src="{{ route('storage', ['folder' => 'foto_produk', 'filename' => $item->hoverFoto->foto_produk]) }}" alt="{{ $item->nama_produk }}" class="bestproduk-image hover-image">
                            @endif
                        </div>
                    </a>
    
                    <div class="bestproduk-info">
                        <div class="bestproduk-name">{{ $item->nama_produk }}</div>
                        <div class="bestproduk-price">
                            @if (!empty($item->diskon))
                                <span class="price-discounted">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                                <span class="price-now">Rp {{ number_format($item->harga_diskon, 0, ',', '.') }}</span>
                            @else
                                <span class="price-now">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                            @endif
                        </div>
                        <div class="bestproduk-kategori">{{ $item->kategori->nama_kategori }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <div class="bestproduk-buttons">
        <a href="{{ route('collection') }}" class="view-all-link">VIEW ALL</a>  
    </div>
</div>
