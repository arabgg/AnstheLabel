@extends('home.layouts.app')

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('home') }}">{{ __('messages.breadcrumb.home') }}</a> / <span>{{ __('messages.breadcrumb.collection') }}</span>
    </div>
@endsection

@section('content')
    <div id="heroCarousel" class="custom-carousel-collection">
        <div class="skeleton-wrapper hero-collection-skeleton">
            <div class="skeleton skeleton-img"></div>
        </div>
        <div class="custom-carousel-collection skeleton-target" style="display:none;">
            @foreach ($hero as $item)
                @if ($item->banner_id >= 1 && $item->banner_id <= 5)
                    <img src="{{ route('storage', ['folder' => 'banner', 'filename' => $item->foto_banner]) }}"
                        class="carousel-image {{ $item->banner_id === 1 ? 'active' : '' }}"
                        alt="{{ $item->nama_banner }}">
                @endif
            @endforeach
        </div>
    </div>

    @include('home.collection.show')
@endsection

@push('scripts')
<script>
    // Carousel Hero
    document.querySelectorAll('.custom-carousel-collection').forEach(carousel => {
        const images = carousel.querySelectorAll('.carousel-image');
        let currentIndex = 0;

        const showNextImage = () => {
            images[currentIndex].classList.remove('active');
            currentIndex = (currentIndex + 1) % images.length;
            images[currentIndex].classList.add('active');
        };

        carousel.addEventListener('click', showNextImage);
    });

    // Show All Product
    function showAllKatalog() {
        const cards = document.querySelectorAll('.produk-card');
        cards.forEach(card => card.classList.remove('produk-hidden'));

        document.getElementById('viewAllButton').style.display = 'none';
        document.getElementById('hideButton').style.display = 'inline-block';
    }

    function hideExtraKatalog() {
        const cards = document.querySelectorAll('.produk-card');
        cards.forEach((card, index) => {
            if (index >= 6) {
                card.classList.add('produk-hidden');
            }
        });

        document.getElementById('viewAllButton').style.display = 'inline-block';
        document.getElementById('hideButton').style.display = 'none';
    }

    // Filter Produk
    function filterByKategori() {
        const selectedKategori = document.getElementById("kategoriFilter").value.toLowerCase();
        const cards = document.querySelectorAll(".produk-card");

        cards.forEach((card, index) => {
            const kategori = card.getAttribute("data-kategori").toLowerCase();
            const isMatch = selectedKategori === "all" || kategori === selectedKategori;
            card.style.display = isMatch ? "block" : "none";
        });

        // Atur tombol view/hide berdasarkan filter
        document.getElementById("viewAllButton").style.display = "none";
        document.getElementById("hideButton").style.display = "none";
    }
</script>
@endpush