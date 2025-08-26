<div class="edition-section">
    <div class="edition-header">
        <h1 class="edition-title">Collection Edition</h1>
    </div>

    <div class="edition-grid" id="katalogGrid">
        @foreach ($edition as $item)
            <div class="edition-card">
                <div class="edition-image-wrapper">
                    {{-- Animasi Skeleton --}}
                    <div class="skeleton-wrapper edition-skeleton">
                        <div class="skeleton skeleton-img"></div>
                    </div>

                    {{-- Konten Utama --}}
                    <a href="{{ route('detail.show', $item->produk_id) }}">
                        <div class="skeleton-target" style="display:none;">
                            <div class="edition-label">COLLECTION</div>
                            @if ($item->fotoUtama)
                                <img src="{{ route('storage', ['folder' => 'foto_produk', 'filename' => $item->fotoUtama->foto_produk]) }}" alt="{{ $item->nama_produk }}" class="edition-image default-image">
                            @endif
                            @if ($item->hoverFoto)
                                <img src="{{ route('storage', ['folder' => 'foto_produk', 'filename' => $item->hoverFoto->foto_produk]) }}" alt="{{ $item->nama_produk }}" class="edition-image hover-image">
                            @endif
                        </div>
                    </a>
                </div>
                
                <div class="edition-info">
                    <div class="edition-color-dot">
                        @foreach ($item->warna as $warnaItem)
                            @if ($warnaItem->warna)
                                <span class="edition-dot" 
                                    style="background-color: {{ $warnaItem->warna->kode_hex ?? '#000000' }};">
                                </span>
                            @endif
                        @endforeach
                    </div>

                    <div class="edition-name">{{ $item->nama_produk }}</div>
                    <div class="edition-kategori">{{ $item->kategori->nama_kategori }}</div>
                </div>
            </div>
        @endforeach
    </div>
</div>