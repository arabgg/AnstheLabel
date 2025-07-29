<div class="produk-section">
    {{-- Sidebar Filter --}}
    <div class="produk-sidebar">
        <h2 class="produk-title">Filters</h2>

        {{-- Aktif Filter Ditampilkan --}}
        @if (!empty($filterkategori))
            <div class="produk-tags">
                <span class="produk-tag">
                    {{ ucfirst(str_replace('_', ' ', $filterkategori)) }}
                    <a href="{{ route('collection') }}">×</a>
                </span>
            </div>
        @endif

        {{-- Filter Form --}}
        <form method="GET" action="{{ route('collection') }}" id="filterForm">
            <fieldset class="produk-filter-box">
                <legend class="produk-filter-title">Sort By</legend>
                <div class="produk-checkbox-group">
                    @foreach ([
                        'new_arrival' => 'New Arrival', 
                        'best_seller' => 'Best Seller', 
                        'hijab' => 'Hijab', 
                        'dress' => 'Dress'
                    ] as $key => $label)
                        <label class="produk-checkbox">
                            <input 
                                type="radio" 
                                name="filter" 
                                value="{{ $key }}"
                                onchange="document.getElementById('filterForm').submit()"
                                {{ $filterkategori === $key ? 'checked' : '' }}>
                            {{ $label }}
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
            @foreach ($show as $index => $item)
               @if (!in_array($item->produk_id, $displayedProdukIds))
            @php
                $displayedProdukIds[] = $item->produk_id;
            @endphp

            <a href="{{ route('detail.show', $item->produk_id) }}" class="produk-card-link">
                <div class="produk-card">
                    <img src="{{ asset('storage/foto_produk/' . $item->foto_produk) }}" alt="{{ $item->produk->nama_produk }}">
                    <h3>{{ $item->produk->nama_produk }}</h3>
                    <p>{{ $item->produk->deskripsi ?? 'Pakaian Muslim Wanita' }}</p>
                    <div class="produk-color-dots">
                        <span class="produk-dot produk-black"></span>
                        <span class="produk-dot produk-pink"></span>
                    </div>
                </div>
            </a>
        @endif

            @endforeach
        </div>

        {{-- Tombol Load More di bawah produk --}}
        <div class="produk-buttons" style="text-align: center; margin-top: 30px;">
            <button id="viewAllButton" onclick="showAllKatalog()">Load More</button>
            <button id="hideButton" onclick="hideExtraKatalog()" style="display: none;">Hide</button>
        </div>
    </div>
</div>
