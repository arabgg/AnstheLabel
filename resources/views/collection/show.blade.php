<div class="produk-section">
    {{-- Sidebar Filter --}}
    <div class="produk-sidebar">
        <h2 class="produk-title">Filters</h2>

        {{-- Aktif Filter Ditampilkan --}}
        @if (!empty($filterkategori))
            <div class="produk-tags">
                @foreach ($filterkategori as $kategoriId)
                    @php
                        $kategoriNama = $kategori->firstWhere('kategori_id', $kategoriId)?->nama_kategori ?? 'Unknown';
                        $remainingFilters = array_diff($filterkategori, [$kategoriId]);
                    @endphp
                    <span class="produk-tag">
                        {{ ucfirst($kategoriNama) }}
                        <a href="{{ route('collection', ['filter' => $remainingFilters]) }}">×</a>
                    </span>
                @endforeach
            </div>
        @endif

        {{-- Filter Form --}}
        <form method="GET" action="{{ route('collection') }}" id="filterForm">
            <fieldset class="produk-filter-box">
                <legend class="produk-filter-title">Sort By</legend>
                <div class="produk-checkbox-group">
                    @foreach ($kategori as $item)
                        <label class="produk-checkbox">
                            <input 
                                type="checkbox" 
                                name="filter[]" 
                                value="{{ $item->kategori_id }}"
                                onchange="document.getElementById('filterForm').submit()"
                                {{ in_array($item->kategori_id, $filterkategori) ? 'checked' : '' }}>
                            {{ ucfirst($item->nama_kategori) }}
                        </label>
                    @endforeach
                </div>
            </fieldset>
        </form>
    </div>


    {{-- Kontainer Konten Produk --}}
    <div style="flex: 1;">
        {{-- Product Grid --}}
        <div class="produk-product-grid">
            @foreach ($produk as $index => $item)
                <a href="{{ route('detail.show', $item->produk_id) }}" class="produk-card-link">
                    <div class="produk-card bestproduk-card {{ $index >= 6 ? 'bestproduk-hidden' : '' }}">
                        <img src="{{ asset('storage/foto_produk/' . $item->fotoUtama->foto_produk) }}" alt="{{ $item->nama_produk }}">
                        <div class="produk-color-dot">
                            @foreach ($item->warna as $warnaItem)
                                @if ($warnaItem->warna)
                                    <span class="produk-dot"
                                        style="background-color: {{ $warnaItem->warna->kode_hex ?? '#000000' }};">
                                    </span>
                                @endif
                            @endforeach
                        </div>
                        <h3>{{ $item->nama_produk }}</h3>
                        <p>{{ $item->kategori->nama_kategori }}</p>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- Tombol Load More di bawah produk --}}
        @if (count($produk) > 6)
            <div class="produk-buttons" style="text-align: center; margin-top: 30px;">
                <button id="viewAllButton" onclick="showAllKatalog()" class="more-link">More</button>
                <button id="hideButton" onclick="hideExtraKatalog()" class="more-link" style="display: none;">Hide</button>
            </div>
        @endif
    </div>
</div>
