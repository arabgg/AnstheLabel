@extends('layouts.app')

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('page') }}">Home</a> / <span>About Us</span>
    </div>
@endsection

@section('content')
    <div class="about-section">
        <div class="about-container">
            <div class="about-content">
                <div class="about-left">
                    <img src="{{ asset('storage/images/ansthelabel.png') }}" alt="Ansthelabel Logo" class="about-logo">
                </div>
                <div class="about-right">
                    <p>
                        Lorem Ipsum Dolor Sit Amet, Consectetur Adipisicing Elit, Sed Do Eiusmod Tempor Incididunt Ut Labore Et Dolore Magna Aliqua. Ut Enim Ad Minim Veniam, Quis Nostrud Exercitation Ullamco Laboris Nisi Ut Aliquip Ex Ea Commodo Consequat. Duis Aute Irure Dolor In Reprehenderit In Voluptate Velit Esse Cillum Dolore Eu Fugiat Nulla Pariatur. Excepteur Sint Occaecat Cupidatat Non Proident, Sunt In Culpa Qui Officia Deserunt Mollit Anim Id Est Laborum.
                    </p>
                </div>
            </div>
        </div>

        <div class="about-recommend">
            <h2>You May Also Like</h2>
            <div class="recommend-grid">
                @foreach ($rekomendasi as $item)
                <div class="recommend-card">
                    <a href="{{ route('detail.show', $item->produk_id) }}">
                        <img src="{{ asset('storage/foto_produk/' . $item->fotoUtama->foto_produk) }}" alt="{{ $item->nama_produk }}">
                        <h3>{{ $item->nama_produk }}</h3>
                        <p>{{ $item->kategori->nama_kategori }}</p>
                    </a>
                </div>
                @endforeach
            </div>
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