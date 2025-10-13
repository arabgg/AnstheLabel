{{-- Header Title & Search --}}
<div class="produk-header">
    <h1>{{ __('messages.title.collection') }}</h1>
    <div class="produk-search-wrapper">
        <form method="GET" action="{{ route('collection') }}" class="produk-search-form">
            <input type="text" name="search" placeholder="{{ __('messages.placeholder.search') }}" value="{{ request('search') }}">
            <button type="submit">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>
</div>

<div class="produk-section">
    {{-- Sidebar Filter --}}
    <div class="produk-sidebar">
        <h2 class="produk-title">Filter:</h2>

        {{-- Aktif Filter Ditampilkan --}}
        <h2>{{ __('messages.category') }}:</h2>
        @if (!empty($filterKategori))
            <div class="produk-tags">
                @foreach ($filterKategori as $kategoriId)
                    @php
                        $kategoriNama = $kategori->firstWhere('kategori_id', $kategoriId)?->nama_kategori ?? 'Unknown';
                        $remainingFilters = array_diff($filterKategori, [$kategoriId]);
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
                <div class="produk-checkbox-group">
                    @foreach ($kategori as $item)
                        <label class="produk-checkbox">
                            <input 
                                type="checkbox" 
                                name="filter[]" 
                                value="{{ $item->kategori_id }}"
                                onchange="document.getElementById('filterForm').submit()"
                                {{ in_array($item->kategori_id, $filterKategori) ? 'checked' : '' }}>
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
                    <div class="produk-card {{ $index >= 6 ? 'produk-hidden' : '' }}">
                        {{-- Skeleton Wrapper --}}
                        <div class="skeleton-wrapper produk-skeleton">
                            <div class="skeleton skeleton-img"></div>
                        </div>

                        {{-- Konten Asli--}}
                        <div class="skeleton-target" style="display:none;">
                            @if (!empty($item->diskon && $item->diskon > 0))
                                <span class="diskon-label-collection">Save {{ $item->diskon_persen }} %</span>
                            @endif
                            
                            <img src="{{ route('storage', ['folder' => 'foto_produk', 'filename' => $item->fotoUtama->foto_produk]) }}" alt="{{ $item->nama_produk }}">
                            
                            <div class="produk-color-dot">
                                @foreach ($item->warna as $warnaItem)
                                    @if ($warnaItem->warna)
                                        <span class="produk-dot"
                                            style="background-color: {{ $warnaItem->warna->kode_hex ?? '#000000' }};">
                                        </span>
                                    @endif
                                @endforeach
                            </div>

                            <div class="collection-price">
                                @if (!empty($item->diskon))
                                    <span class="produk-price-discounted">IDR {{ number_format($item->harga, 0, ',', '.') }}</span>
                                    <span class="produk-price-now">IDR {{ number_format($item->harga_diskon, 0, ',', '.') }}</span>
                                @else
                                    <span class="produk-price-now">IDR {{ number_format($item->harga, 0, ',', '.') }}</span>
                                @endif
                            </div>
                            <h3>{{ $item->nama_produk }}</h3>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>


        {{-- Tombol Load More di bawah produk --}}
        @if (count($produk) > 6)
            <div class="produk-buttons" style="text-align: center; margin-top: 30px;">
                <button id="viewAllButton" onclick="showAllKatalog()" class="more-link">{{ __('messages.button.more') }}</button>
                <button id="hideButton" onclick="hideExtraKatalog()" class="more-link" style="display: none;">{{ __('messages.button.hide') }}</button>
            </div>
        @endif
    </div>
</div>
