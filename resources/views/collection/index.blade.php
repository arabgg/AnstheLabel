@extends('layouts.app')

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('page') }}">Home</a> / <span>Collection</span>
    </div>
@endsection

@section('content')
    <div id="heroCarouselCustom2" class="custom-carousel">
        <img src="{{ asset('storage/images/hero/hero5.png') }}" class="carousel-image active" alt="Hero 5">
        <img src="{{ asset('storage/images/hero/hero6.png') }}" class="carousel-image" alt="Hero 6">
        <img src="{{ asset('storage/images/hero/hero7.png') }}" class="carousel-image" alt="Hero 7">
        <img src="{{ asset('storage/images/hero/hero8.png') }}" class="carousel-image" alt="Hero 8">
    </div>

    @include('collection.show')
@endsection

@push('scripts')
<script>
    // Carousel Hero
    document.querySelectorAll('.custom-carousel').forEach(carousel => {
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
            if (index >= 2) {
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