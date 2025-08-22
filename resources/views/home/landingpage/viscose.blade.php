<div class="viscose-section">
    <div class="viscose-header">
        <h1 class="viscose-title">Viscose Edition</h1>
    </div>

    <div class="viscose-grid" id="katalogGrid">
        @foreach ($viscose as $index => $item)
            <div class="viscose-card">
                <div class="viscose-image-wrapper">
                    {{-- Animasi Skeleton --}}
                    <div class="skeleton-wrapper viscose-skeleton">
                        <div class="skeleton skeleton-img"></div>
                    </div>

                    {{-- Konten Utama --}}
                    <div class="skeleton-target" style="display:none;">
                        <span class="viscose-label">Premium</span>
                        <img src="{{ asset('storage/images/bestproduk/' . $item['image']) }}" alt="{{ $item['nama'] }}" class="viscose-image default-image">
                        
                        @if (!empty($item['image_hover']))
                            <img src="{{ asset('storage/images/bestproduk/' . $item['image_hover']) }}" alt="{{ $item['nama'] }}" class="viscose-image hover-image">
                        @endif
                    </div>
                </div>
                
                <div class="viscose-info">
                    <div class="viscose-color-dot">
                        @foreach ($item['warna'] as $warna)
                            <span class="viscose-dot" style="background-color: {{ $warna }};"></span>
                        @endforeach
                    </div>

                    <div class="viscose-name">{{ $item['nama'] }}</div>
                    <div class="viscose-kategori">{{ $item['kategori'] }}</div>
                    <div class="viscose-series">VISCOSE MATERIAL</div>
                </div>
            </div>
        @endforeach
    </div>
</div>