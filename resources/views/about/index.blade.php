@extends('layouts.app')

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('page') }}">Home</a> / <span>About Us</span>
    </div>
@endsection

@section('content')
    <div id="heroCarouselCustom2" class="custom-carousel">
        <img src="{{ asset('storage/images/hero/hero5.png') }}" class="carousel-image active" alt="Hero 5">
        <img src="{{ asset('storage/images/hero/hero6.png') }}" class="carousel-image" alt="Hero 6">
        <img src="{{ asset('storage/images/hero/hero7.png') }}" class="carousel-image" alt="Hero 7">
        <img src="{{ asset('storage/images/hero/hero8.png') }}" class="carousel-image" alt="Hero 8">
    </div>
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