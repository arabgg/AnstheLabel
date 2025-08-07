<div class="bestproduk-section">
    <div class="bestproduk-header">
        <h1 class="bestproduk-title">New Arrival</h1>
    </div>

    <div class="bestproduk-grid" id="katalogGrid">
        @foreach ($bestproduk as $index => $item)
            <div class="bestproduk-card">
                <a href="{{ route('detail.show', $item->produk_id) }}">
                    <div class="bestproduk-image-wrapper">
                        {{-- Gambar utama dengan status_foto = 1 --}}
                        @if ($item->fotoUtama)
                            <img src="{{ asset('storage/foto_produk/' . $item->fotoUtama->foto_produk) }}" alt="{{ $item->nama_produk }}" class="bestproduk-image default-image">
                        @endif

                        {{-- Gambar hover: salah satu dari foto dengan status_foto = 0 --}}
                        @php
                            $hoverFoto = $item->foto->firstWhere('status_foto', 0);
                        @endphp
                        @if ($hoverFoto)
                            <img src="{{ asset('storage/foto_produk/' . $hoverFoto->foto_produk) }}" alt="{{ $item->nama_produk }}" class="bestproduk-image hover-image">
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
                    <div class="bestproduk-kategori">{{ $item->kategori->nama_kategori ?? '-' }}</div>
                </div>
            </div>
        @endforeach
    </div>
    
    <div class="bestproduk-buttons">
        <a href="{{ route('collection') }}" class="view-all-link">VIEW ALL</a>  
    </div>
</div>
