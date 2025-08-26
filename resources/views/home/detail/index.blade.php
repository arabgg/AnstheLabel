@extends('home.layouts.app')

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('home') }}">Home</a> / 
        <a href="{{ route('collection') }}">Collection</a> / 
        <span>{{ $produk->nama_produk }}</span>
    </div>
@endsection

@section('content')
    <div class="detail-product-page">
        <div class="detail-container">
            <div class="detail-product-wrapper">
                <!-- Left: Product Images -->
                <div class="detail-product-images">
                    {{-- Skeleton Wrapper --}}
                    <div class="skeleton-wrapper">
                        <div class="skeleton skeleton-main-img"></div>
                    </div>

                    <div class="skeleton-target" style="display:none;">
                        {{-- Foto Utama --}}
                        <img class="detail-main-image"
                            src="{{ asset('storage/foto_produk/' . $produk->fotoUtama->foto_produk) }}"
                            alt="{{ $produk->nama_produk }}">
    
                        {{-- Foto Thumbnail --}}
                        <div class="detail-thumbnail-wrapper">
                            @foreach ($produk->foto->where('status_foto', 0) as $foto)
                                <img src="{{ asset('storage/foto_produk/' . $foto->foto_produk) }}"
                                    alt="Thumbnail {{ $loop->iteration }}">
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Right: Product Info -->
                <div class="detail-product-info">
                    <div class="detail-section">
                        <h2 class="detail-product-name">{{ $produk->nama_produk }}</h2>
                        <p>{{ $produk->kategori->nama_kategori }}</p>
                    </div>

                    <div class="detail-section-info">
                        <div class="detail-price">
                            @if (!empty($produk->diskon))
                                <span class="detail-price-discounted">Rp {{ number_format($produk->harga, 0, ',', '.') }}</span>
                                <span class="detail-price-now">Rp {{ number_format($produk->harga_diskon, 0, ',', '.') }}</span>
                            @else
                                <span class="detail-price-now">Rp {{ number_format($produk->harga, 0, ',', '.') }}</span>
                            @endif
                        </div>

                        <form id="cart-form" action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="produk_id" value="{{ $produk->produk_id }}">

                            <!-- Warna -->
                            <div class="detail-color-wrapper">
                                <p>Color Available</p>
                                @if ($produk->warna->isNotEmpty())
                                    <div class="detail-color-dot">
                                        @foreach ($produk->warna as $warnaItem)
                                            @if ($warnaItem->warna)
                                                <label style="cursor: pointer;">
                                                    <input type="radio" name="warna" value="{{ $warnaItem->warna->warna_id }}" style="display:none;" required/>
                                                    <span class="detail-dot" 
                                                        style="background-color: {{ $warnaItem->warna->kode_hex ?? '#000000' }};">
                                                    </span>
                                                </label>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <!-- Ukuran -->
                            <div class="detail-size-wrapper">    
                                <p>Size Available</p>
                                @if ($produk->ukuran->isNotEmpty())
                                    <div class="detail-size">
                                        @foreach ($produk->ukuran as $sizeItem)
                                            @if ($sizeItem->produk)
                                                <label style="cursor:pointer; margin-right: 8px; user-select:none;">
                                                    <input type="radio" name="ukuran" value="{{ $sizeItem->ukuran->ukuran_id }}" style="display:none;" required/>
                                                    <span class="detail-size-nama">
                                                        {{ $sizeItem->ukuran->nama_ukuran }}
                                                    </span>
                                                </label>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <p class="detail-quantity-label">Quantity:</p>
                            <div class="detail-quantity-wrapper" style="margin-top: 10px;">
                                <input type="number" name="quantity" value="1" min="1" required>
                            </div>

                            <div class="detail-buy-wrapper" style="margin-top: 15px;">
                                <button type="submit" name="action" value="buy_now" class="btn-buy-now">Buy Now</button>
                            </div>

                            <div class="detail-cart-wrapper" style="margin-top: 10px;">
                                <button type="button" id="btn-add-to-cart" class="btn-add-cart">
                                    <i class="fa fa-cart-shopping" style="margin-right: 8px"></i> Add to Cart
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="detail-deskripsi-wrapper" style="margin-top: 20px;">
                        <h3>Detail Produk</h3>
                        <div class="detail-deskripsi-produk">
                            <p>{{ $produk->deskripsi }}</p>
                        </div>

                        @if ($produk->ukuran->isNotEmpty())
                            <div class="detail-deskripsi-bahan">
                                @foreach ($produk->ukuran as $sizeItem)
                                    @if ($sizeItem->produk)
                                        <p>{{ $sizeItem->ukuran->nama_ukuran }} - {{ $sizeItem->ukuran->deskripsi }}</p>
                                    @endif
                                @endforeach
                            </div>
                        @endif

                        <div class="detail-deskripsi-ukuran">
                            <h6>Jenis Bahan : {{ $produk->bahan->nama_bahan }}</h6>
                            <p>{{ $produk->bahan->deskripsi }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="detail-recommend" style="margin-top: 30px;">
        <h2>You May Also Like</h2>
        <div class="detail-recommend-grid">
            @foreach ($rekomendasi as $item)
            <div class="detail-recommend-card">
                <a href="{{ route('detail.show', $item->produk_id) }}">
                    <img src="{{ asset('storage/foto_produk/' . $item->fotoUtama->foto_produk) }}" alt="{{ $item->nama_produk }}">
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
function showToast(icon, title) {
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: icon,
        title: title,
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true
    });
}

function validasiWarnaUkuran(form) {
    const warnaDipilih = form.querySelector('input[name="warna"]:checked');
    const ukuranDipilih = form.querySelector('input[name="ukuran"]:checked');

    if (!warnaDipilih) {
        showToast('warning', 'Silakan pilih warna!');
        return false;
    }

    if (!ukuranDipilih) {
        showToast('warning', 'Silakan pilih ukuran!');
        return false;
    }

    return true;
}

document.getElementById('btn-add-to-cart').addEventListener('click', function (e) {
    e.preventDefault();

    const form = document.getElementById('cart-form');
    if (!validasiWarnaUkuran(form)) return;

    const formData = new FormData(form);
    formData.append('action', 'add_to_cart');

    fetch("{{ route('cart.add') }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast('success', 'Barang ditambahkan ke cart!');
        } else {
            showToast('error', 'Gagal menambahkan barang ke cart.');
        }
    })
    .catch(() => {
        showToast('error', 'Terjadi kesalahan saat menambahkan ke cart.');
    });
});

document.querySelector('.btn-buy-now').addEventListener('click', function (e) {
    const form = document.getElementById('cart-form');
    if (!validasiWarnaUkuran(form)) {
        e.preventDefault();
    }
});
</script>
@endpush