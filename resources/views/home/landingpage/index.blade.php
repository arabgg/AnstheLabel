@extends('home.layouts.app')

@section('content')
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="skeleton-wrapper hero-skeleton">
            <div class="skeleton skeleton-img"></div>
        </div>

        <div id="heroCarouselCustom" class="custom-carousel skeleton-target" style="display:none;">
            @foreach ($hero as $item)
                @if ($item->banner_id >= 1 && $item->banner_id <= 4)
                    <img src="{{ asset('storage/images/banner/' . $item->foto_banner) }}"
                        class="carousel-image {{ $item->banner_id === 1 ? 'active' : '' }}"
                        alt="{{ $item->nama_banner }}">
                @endif
            @endforeach
        </div>
    </div>

    @include('home.landingpage.newarrival')
    
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="skeleton-wrapper hero-skeleton">
            <div class="skeleton skeleton-img"></div>
        </div>
        
        <div id="heroCarousel" class="custom-carousel skeleton-target" style="display:none;">
            @foreach ($hero as $item)
                @if ($item->banner_id >= 5 && $item->banner_id <= 8)
                    <img src="{{ asset('storage/images/banner/' . $item->foto_banner) }}"
                        class="carousel-image {{ $item->banner_id === 5 ? 'active' : '' }}"
                        alt="{{ $item->nama_banner }}">
                @endif
            @endforeach
        </div>
    </div>

    @include('home.landingpage.bestproduk')

    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="skeleton-wrapper hero-skeleton">
            <div class="skeleton skeleton-img"></div>
        </div>
        
        <div id="heroCarousel" class="custom-carousel skeleton-target" style="display:none;">
            @foreach ($hero as $item)
                @if ($item->banner_id >= 5 && $item->banner_id <= 8)
                    <img src="{{ asset('storage/images/banner/' . $item->foto_banner) }}"
                        class="carousel-image {{ $item->banner_id === 5 ? 'active' : '' }}"
                        alt="{{ $item->nama_banner }}">
                @endif
            @endforeach
        </div>
    </div>

    @include('home.landingpage.bestseller')    

    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="skeleton-wrapper hero-skeleton">
            <div class="skeleton skeleton-img"></div>
        </div>
        
        <div id="heroCarousel" class="custom-carousel skeleton-target" style="display:none;">
            @foreach ($hero as $item)
                @if ($item->banner_id >= 5 && $item->banner_id <= 8)
                    <img src="{{ asset('storage/images/banner/' . $item->foto_banner) }}"
                        class="carousel-image {{ $item->banner_id === 5 ? 'active' : '' }}"
                        alt="{{ $item->nama_banner }}">
                @endif
            @endforeach
        </div>
    </div>

    @include('home.landingpage.edition')    
    
    <div class="collection-section container">
    <div class="hero-grid">
        @foreach ($hero as $index => $item)
            @if ($item->banner_id >= 7 && $item->banner_id <= 8)
                <div class="hero-item {{ $index === 0 ? 'left' : 'right' }}">
                    <img src="{{ asset('storage/images/banner/' . $item->foto_banner) }}"
                        alt="{{ $item->nama_banner }}">
                </div>
            @endif
        @endforeach
    </div>
</div>
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