@extends('home.layouts.app')

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('home') }}">Home</a> / <span>Collection</span>
    </div>
@endsection

@section('content')
    <div id="heroCarousel" class="custom-carousel-collection">
        <div class="skeleton-wrapper hero-collection-skeleton">
            <div class="skeleton skeleton-img"></div>
        </div>
        <div class="custom-carousel-collection skeleton-target" style="display:none;">
            @foreach ($hero as $item)
                @if ($item->banner_id >= 5 && $item->banner_id <= 8)
                    <img src="{{ route('storage', ['folder' => 'banner', 'filename' => $item->foto_banner]) }}"
                        class="carousel-image {{ $item->banner_id === 5 ? 'active' : '' }}"
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
        const cards = document.querySelectorAll('.bestproduk-card');
        cards.forEach(card => card.classList.remove('bestproduk-hidden'));

        document.getElementById('viewAllButton').style.display = 'none';
        document.getElementById('hideButton').style.display = 'inline-block';
    }

    function hideExtraKatalog() {
        const cards = document.querySelectorAll('.bestproduk-card');
        cards.forEach((card, index) => {
            if (index >= 6) {
                card.classList.add('bestproduk-hidden');
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

    // Auto scroll ke produk setelah filter
    @if(request()->has('filter'))
    window.addEventListener('load', function () {
        const target = document.getElementById("katalog");
        if (target) {
            target.scrollIntoView({ behavior: "smooth" });
        }
    });
    @endif
</script>
@endpush