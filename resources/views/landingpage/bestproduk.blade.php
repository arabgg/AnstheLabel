<div class="bestproduk-section">
    <div class="bestproduk-header">
        <h1 class="bestproduk-title">Best Product</h1>
        
        <div class="bestproduk-buttons">
            <a href="{{ route('collection') }}" class="view-all-link">View All</a>  
        </div>
    </div>

    <div class="bestproduk-grid" id="katalogGrid">
        @foreach ($bestproduk as $index => $item)
            <div class="bestproduk-card">
                <div class="bestproduk-image-wrapper">
                    <img src="{{ asset('storage/images/bestproduk/' . $item['image']) }}" alt="{{ $item['nama'] }}" class="bestproduk-image default-image">
                    @if (!empty($item['image_hover']))
                        <img src="{{ asset('storage/images/bestproduk/' . $item['image_hover']) }}" alt="{{ $item['nama'] }}" class="bestproduk-image hover-image">
                    @endif
                </div>
                
                <div class="bestproduk-info">
                    <div class="bestproduk-name">{{ $item['nama'] }}</div>
                    <div class="bestproduk-kategori">{{ $item['kategori'] }}</div>
                    @if (!empty($item['warna']))
                        <div class="bestproduk-color-dots">
                            @foreach ($item['warna'] as $warna)
                                <span class="bestproduk-dot" style="background-color: {{ $warna }};"></span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    {{-- <script>
        function showAllKatalog() {
            const cards = document.querySelectorAll('.bestproduk-card');
            cards.forEach(card => card.classList.remove('bestproduk-hidden'));

            document.getElementById('viewAllButton').style.display = 'none';
            document.getElementById('hideButton').style.display = 'inline-block';
        }

        function hideExtraKatalog() {
            const cards = document.querySelectorAll('.bestproduk-card');
            cards.forEach((card, index) => {
                if (index >= 3) {
                    card.classList.add('bestproduk-hidden');
                }
            });

            document.getElementById('viewAllButton').style.display = 'inline-block';
            document.getElementById('hideButton').style.display = 'none';
        }
    </script> --}}
</div>
