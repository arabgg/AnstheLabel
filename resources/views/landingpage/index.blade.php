@extends('layouts.app')

@section('content')
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
        <div id="heroCarouselCustom" class="custom-carousel">
            <img src="{{ asset('storage/images/hero/hero1.png') }}" class="carousel-image active" alt="Hero 1">
            <img src="{{ asset('storage/images//hero/hero2.png') }}" class="carousel-image" alt="Hero 2">
            <img src="{{ asset('storage/images/hero/hero3.png') }}" class="carousel-image" alt="Hero 3">
            <img src="{{ asset('storage/images/hero/hero4.png') }}" class="carousel-image" alt="Hero 4">
        </div>

        <!-- Tombol Buy Now -->
        {{-- <a href="#" target="_blank" class="buy-now-btn">BUY NOW</a> --}}
    </div>

    @include('landingpage.bestproduk')

    <div id="heroCarouselCustom2" class="custom-carousel">
        <img src="{{ asset('storage/images/hero/hero5.png') }}" class="carousel-image active" alt="Hero 5">
        <img src="{{ asset('storage/images/hero/hero6.png') }}" class="carousel-image" alt="Hero 6">
        <img src="{{ asset('storage/images/hero/hero7.png') }}" class="carousel-image" alt="Hero 7">
        <img src="{{ asset('storage/images/hero/hero8.png') }}" class="carousel-image" alt="Hero 8">
    </div>

    @include('landingpage.viscose')    

    <div id="heroCarouselCustom2" class="custom-carousel">
        <img src="{{ asset('storage/images/hero/hero5.png') }}" class="carousel-image active" alt="Hero 5">
        <img src="{{ asset('storage/images/hero/hero6.png') }}" class="carousel-image" alt="Hero 6">
        <img src="{{ asset('storage/images/hero/hero7.png') }}" class="carousel-image" alt="Hero 7">
        <img src="{{ asset('storage/images/hero/hero8.png') }}" class="carousel-image" alt="Hero 8">
    </div>

    @include('landingpage.bestseller')    

    <div id="heroCarouselCustom2" class="custom-carousel">
        <img src="{{ asset('storage/images/hero/hero5.png') }}" class="carousel-image active" alt="Hero 5">
        <img src="{{ asset('storage/images/hero/hero6.png') }}" class="carousel-image" alt="Hero 6">
        <img src="{{ asset('storage/images/hero/hero7.png') }}" class="carousel-image" alt="Hero 7">
        <img src="{{ asset('storage/images/hero/hero8.png') }}" class="carousel-image" alt="Hero 8">
    </div>

    @include('landingpage.cooltech') 

    
    <div id="heroCarouselCustom2" class="custom-carousel">
        <img src="{{ asset('storage/images/hero/hero5.png') }}" class="carousel-image active" alt="Hero 5">
        <img src="{{ asset('storage/images/hero/hero6.png') }}" class="carousel-image" alt="Hero 6">
        <img src="{{ asset('storage/images/hero/hero7.png') }}" class="carousel-image" alt="Hero 7">
        <img src="{{ asset('storage/images/hero/hero8.png') }}" class="carousel-image" alt="Hero 8">
    </div>

    @include('landingpage.collection')
@endsection

@push('scripts')
<script>
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