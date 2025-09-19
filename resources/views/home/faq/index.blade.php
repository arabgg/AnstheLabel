@extends('home.layouts.app')

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('home') }}">Home</a> / <span>FAQ</span>
    </div>
@endsection

@section('content')
    <div class="faq-section">
        <h2>Frequently Asked Questions</h2>
        <div class="faq-container">
            @foreach ($faqs as $faq)
                <div class="faq-item">
                    <button class="faq-question">{{ $faq->pertanyaan }}</button>
                    <div class="faq-answer">
                        {!! $faq->jawaban !!}
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="about-recommend">
        <h2>You May Also Like</h2>
        <div class="recommend-grid">
            @foreach ($rekomendasi as $item)
                <div class="recommend-card">
                    <a href="{{ route('detail.show', $item->produk_id) }}">
                        <img src="{{ route('storage', ['folder' => 'foto_produk', 'filename' => $item->fotoUtama->foto_produk]) }}"
                            alt="{{ $item->nama_produk }}">
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
        // FAQ toggle
        document.querySelectorAll('.faq-question').forEach(button => {
            button.addEventListener('click', () => {
                const item = button.parentElement;

                // Tutup semua item lain
                document.querySelectorAll('.faq-item').forEach(faq => {
                    if (faq !== item) {
                        faq.classList.remove('active');
                    }
                });

                // Toggle item yang diklik
                item.classList.toggle('active');
            });
        });
    </script>
@endpush
