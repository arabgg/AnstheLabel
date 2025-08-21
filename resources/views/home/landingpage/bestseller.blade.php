<div class="bestseller-section">
    <div class="bestseller-header">
        <h1 class="bestseller-title">Best Seller</h1>
    </div>

    <div class="bestseller-grid" id="katalogGrid">
        @foreach ($bestseller as $index => $item)
            <div class="bestseller-card">
                <a href="{{ route('detail.show', $item->produk_id) }}">
                    <div class="bestseller-image-wrapper">
                        {{-- Animasi Skeleton --}}
                        <div class="skeleton-wrapper">
                            <div class="skeleton skeleton-img"></div>
                        </div>

                        {{-- Konten Utama --}}
                        <div class="skeleton-target" style="display:none;">
                            @if (!empty($item->diskon))
                                <span class="diskon-label">Save {{ $item->diskon_persen }} %</span>
                            @endif
                            {{-- Gambar utama dengan status_foto = 1 --}}
                            @if ($item->fotoUtama)
                                <img src="{{ asset('storage/foto_produk/' . $item->fotoUtama->foto_produk) }}" alt="{{ $item->nama_produk }}" class="bestseller-image default-image">
                            @endif

                            {{-- Gambar hover: salah satu dari foto dengan status_foto = 0 --}}
                            @php
                                $hoverFoto = $item->foto->firstWhere('status_foto', 0);
                            @endphp
                            @if ($hoverFoto)
                                <img src="{{ asset('storage/foto_produk/' . $hoverFoto->foto_produk) }}" alt="{{ $item->nama_produk }}" class="bestseller-image hover-image">
                            @endif
                        </div>
                    </div>
                </a>

                <div class="bestseller-info">
                    <div class="bestseller-name">{{ $item->nama_produk }}</div>
                    <div class="bestseller-price">
                        @if (!empty($item->diskon))
                            <span class="price-discounted">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                            <span class="price-now">Rp {{ number_format($item->harga_diskon, 0, ',', '.') }}</span>
                        @else
                            <span class="price-now">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                        @endif
                    </div>
                    <div class="bestseller-kategori">{{ $item->kategori->nama_kategori ?? '-' }}</div>
                </div>
            </div>
        @endforeach
    </div>
</div>
