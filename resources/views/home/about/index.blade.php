@extends('home.layouts.app')

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('home') }}">Home</a> / <span>About Us</span>
    </div>
@endsection

@section('content')
    <div class="about-section">
        <div class="about-container">
            <div class="about-content">
                <div class="about-left">
                    <img src="{{ route('storage', ['folder' => 'page', 'filename' => 'ansthelabel.png']) }}" alt="Ansthelabel Logo" class="about-logo">
                </div>
                <div class="about-right">
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="about-recommend">
        <h2>You May Also Like</h2>
        <div class="recommend-grid">
            @foreach ($rekomendasi as $item)
            <div class="recommend-card">
                <a href="{{ route('detail.show', $item->produk_id) }}">
                    <img src="{{ route('storage', ['folder' => 'foto_produk', 'filename' => $item->fotoUtama->foto_produk]) }}" alt="{{ $item->nama_produk }}">
                    <h3>{{ $item->nama_produk }}</h3>
                    <p>{{ $item->kategori->nama_kategori }}</p>
                </a>
            </div>
            @endforeach
        </div>
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