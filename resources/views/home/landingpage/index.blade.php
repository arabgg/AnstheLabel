@extends('home.layouts.app')

@section('content')
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="skeleton-wrapper hero-skeleton">
            <div class="skeleton skeleton-img"></div>
        </div>

        <div id="heroCarouselCustom" class="custom-carousel skeleton-target" style="display:none;">
            <img src="{{ asset('storage/images/hero/hero1.jpg') }}" class="carousel-image active" alt="Hero 1">
            <img src="{{ asset('storage/images//hero/hero2.jpg') }}" class="carousel-image" alt="Hero 2">
            <img src="{{ asset('storage/images/hero/hero3.avif') }}" class="carousel-image" alt="Hero 3">
            <img src="{{ asset('storage/images/hero/hero4.avif') }}" class="carousel-image" alt="Hero 4">
        </div>
    </div>

    @include('home.landingpage.newarrival')
    
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="skeleton-wrapper hero-skeleton">
            <div class="skeleton skeleton-img"></div>
        </div>
        
        <div id="heroCarousel" class="custom-carousel skeleton-target" style="display:none;">
            <img src="{{ asset('storage/images/hero/hero5.avif') }}" class="carousel-image active" alt="Hero 5">
            <img src="{{ asset('storage/images/hero/hero6.avif') }}" class="carousel-image" alt="Hero 6">
            <img src="{{ asset('storage/images/hero/hero7.avif') }}" class="carousel-image" alt="Hero 7">
            <img src="{{ asset('storage/images/hero/hero8.avif') }}" class="carousel-image" alt="Hero 8">
        </div>
    </div>

    @include('home.landingpage.bestproduk')

    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="skeleton-wrapper hero-skeleton">
            <div class="skeleton skeleton-img"></div>
        </div>
        
        <div id="heroCarousel" class="custom-carousel skeleton-target" style="display:none;">
            <img src="{{ asset('storage/images/hero/hero5.avif') }}" class="carousel-image active" alt="Hero 5">
            <img src="{{ asset('storage/images/hero/hero6.avif') }}" class="carousel-image" alt="Hero 6">
            <img src="{{ asset('storage/images/hero/hero7.avif') }}" class="carousel-image" alt="Hero 7">
            <img src="{{ asset('storage/images/hero/hero8.avif') }}" class="carousel-image" alt="Hero 8">
        </div>
    </div>

    @include('home.landingpage.bestseller')    

    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="skeleton-wrapper hero-skeleton">
            <div class="skeleton skeleton-img"></div>
        </div>
        
        <div id="heroCarousel" class="custom-carousel skeleton-target" style="display:none;">
            <img src="{{ asset('storage/images/hero/hero5.avif') }}" class="carousel-image active" alt="Hero 5">
            <img src="{{ asset('storage/images/hero/hero6.avif') }}" class="carousel-image" alt="Hero 6">
            <img src="{{ asset('storage/images/hero/hero7.avif') }}" class="carousel-image" alt="Hero 7">
            <img src="{{ asset('storage/images/hero/hero8.avif') }}" class="carousel-image" alt="Hero 8">
        </div>
    </div>

    @include('home.landingpage.viscose')    
    
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="skeleton-wrapper hero-skeleton">
            <div class="skeleton skeleton-img"></div>
        </div>
        
        <div id="heroCarousel" class="custom-carousel skeleton-target" style="display:none;">
            <img src="{{ asset('storage/images/hero/hero5.avif') }}" class="carousel-image active" alt="Hero 5">
            <img src="{{ asset('storage/images/hero/hero6.avif') }}" class="carousel-image" alt="Hero 6">
            <img src="{{ asset('storage/images/hero/hero7.avif') }}" class="carousel-image" alt="Hero 7">
            <img src="{{ asset('storage/images/hero/hero8.avif') }}" class="carousel-image" alt="Hero 8">
        </div>
    </div>

    @include('home.landingpage.collection')
@endsection

@push('scripts')
<script>
    let arrivalIndex = 0;

    function moveArrivalCarousel(direction) {
        const carousel = document.getElementById('arrival-carousel');
        const items = carousel.querySelectorAll('.arrival-carousel-item');
        const totalItems = items.length;
        const itemWidth = items[0].offsetWidth;

        arrivalIndex += direction;
        if (arrivalIndex < 0) arrivalIndex = totalItems - 1;
        if (arrivalIndex >= totalItems) arrivalIndex = 0;

        carousel.style.transform = `translateX(-${arrivalIndex * itemWidth}px)`;
    }

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
</script>
@endpush