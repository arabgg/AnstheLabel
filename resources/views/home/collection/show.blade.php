{{-- Header Title & Search --}}
<div class="produk-header">
    <h1>{{ __('messages.title.collection') }}</h1>
    <div class="produk-button-wrapper">
        <div class="produk-filter-wrapper">
            {{-- FILTER BAHAN --}}
            <form method="GET" action="{{ route('collection') }}" class="produk-bahan-form">
                <select name="bahan[]" onchange="this.form.submit()">
                    <option value="">{{ __('messages.placeholder.bahan') }}</option>
                    @foreach ($bahan as $b)
                        <option value="{{ $b->bahan_id }}" {{ in_array($b->bahan_id,$filterBahan)?'selected':'' }}>
                            {{ ucfirst($b->nama_bahan) }}
                        </option>
                    @endforeach
                </select>
            </form>
    
            {{-- FILTER WARNA --}}
            <form method="GET" action="{{ route('collection') }}" class="produk-warna-form">
                <select name="warna[]" onchange="this.form.submit()">
                    <option value="">{{ __('messages.placeholder.warna') }}</option>
                    @foreach ($warna as $w)
                        <option value="{{ $w->warna_id }}" {{ in_array($w->warna_id,$filterWarna)?'selected':'' }}>
                            {{ ucfirst($w->nama_warna) }}
                        </option>
                    @endforeach
                </select>
            </form>
    
            {{-- FILTER UKURAN --}}
            <form method="GET" action="{{ route('collection') }}" class="produk-ukuran-form">
                <select name="ukuran[]" onchange="this.form.submit()">
                    <option value="">{{ __('messages.placeholder.ukuran') }}</option>
                    @foreach ($ukuran as $u)
                        <option value="{{ $u->ukuran_id }}" {{ in_array($u->ukuran_id,$filterUkuran)?'selected':'' }}>
                            {{ ucfirst($u->nama_ukuran) }}
                        </option>
                    @endforeach
                </select>
            </form>
    
            {{-- RESET FILTER --}}
            <a href="{{ route('collection') }}" class="produk-sort-form">
                <button type="button" >
                    {{ __('messages.placeholder.reset') }}
                </button>
            </a>
        </div>

        <div class="produk-search-wrapper">
            {{-- SORT --}}
            <form method="GET" action="{{ route('collection') }}" class="produk-sort-form">
                <select name="sort" onchange="this.form.submit()">
                    <option value="">{{ __('messages.placeholder.sort') }}</option>
                    <option value="terbaru"  {{ $sort=='terbaru'?'selected':'' }}>Terbaru</option>
                    <option value="termahal" {{ $sort=='termahal'?'selected':'' }}>Termahal</option>
                </select>
            </form>

            {{-- SEARCH --}}
            <form method="GET" action="{{ route('collection') }}" class="produk-search-form">
                <input type="text" name="search" placeholder="{{ __('messages.placeholder.search') }}" value="{{ request('search') }}">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>
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
                            
                            @if($item->fotoUtama && $item->fotoUtama->foto_produk)
                                <img src="{{ route('storage', ['folder' => 'foto_produk', 'filename' => $item->fotoUtama->foto_produk]) }}" alt="{{ $item->nama_produk }}">
                            @else
                                <span>Image not available</span>
                            @endif
                            
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
