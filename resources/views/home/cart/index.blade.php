@extends('home.layouts.app')

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('home') }}">{{ __('messages.breadcrumb.home') }}</a> / 
        <span>Cart</span>
    </div>
@endsection

@section('content')
<div class="cart-container">
    <a href="{{ route('home') }}" class="cart-logo"> 
        <img src="{{ route('storage', ['folder' => 'page', 'filename' => 'ansthelabel.png']) }}" alt="Ansthelabel Logo">
    </a>

    <h2>{{ __('messages.title.cart') }}</h2>

    @if(empty($cart))
        <p>{{ __('messages.title.null_cart') }}</p>
    @else
        <table class="cart-table">
            <thead>
                <tr>
                    <th>{{ __('messages.table.product') }}</th>
                    <th>{{ __('messages.table.amount') }}</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cart as $index => $item)
                <tr>
                    <!-- Kolom Produk -->
                    <td class="product-cell">
                        <div class="product-info">
                            <img src="{{ route('storage', ['folder' => 'foto_produk', 'filename' => $item['foto']]) }}" alt="{{ $item['nama'] }}">
                            <div class="product-details">
                                <div class="product-name">{{ $item['nama'] }}</div>
                                <div class="product-meta">
                                    @if (!empty($item['diskon']))
                                        <span class="cart-price-discounted">IDR {{ number_format($item['harga'], 0, ',', '.') }}</span>
                                        <span class="cart-price-now">IDR {{ number_format($item['harga_diskon'], 0, ',', '.') }}</span>
                                    @else
                                        <span class="cart-price-now">IDR {{ number_format($item['harga'], 0, ',', '.') }}</span>
                                    @endif
                                </div>
                                <div class="product-meta">Color : {{ $item['warna_nama'] ?? '-' }}</div>
                                <div class="product-meta">Size : {{ $item['ukuran_nama'] ?? '-' }}</div>
                            </div>
                        </div>
                    </td>

                    <!-- Kolom Amount -->
                    <td class="amount-cell">
                        <form action="{{ route('cart.update') }}" method="POST" class="quantity-form">
                            @csrf
                            <input type="hidden" name="index" value="{{ $index }}">
                            <div class="quantity-controls">
                                <button type="button" class="btn-decrease">-</button>
                                <input type="text" name="quantity" value="{{ $item['quantity'] }}" readonly>
                                <button type="button" class="btn-increase">+</button>
                            </div>
                        </form>

                        <form action="{{ route('cart.remove') }}" method="POST" style="display:inline-block;">
                            {{-- Tombol hapus --}}
                            @csrf
                            <input type="hidden" name="index" value="{{ $index }}">
                            <button type="submit" style="cursor: pointer; background: none; border: none; margin-left: 5px"><i class="fa-regular fa-trash-can"></i></button>
                        </form>
                    </td>

                    <!-- Kolom Total -->
                    <td class="total-cell">
                        <div class="total-price">
                            IDR {{ number_format($item['harga_diskon'] * $item['quantity'], 2, ',', '.') }}
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Summary -->
        <div class="cart-summary">
            <div class="summary-row">
                <h3>{{ __('messages.table.total') }}</h3>
                <span>
                    IDR {{ number_format(collect($cart)->sum(fn($i) => $i['harga'] * $i['quantity']), 2, ',', '.') }}
                </span>
            </div>
            <div class="summary-buttons">
                <a href="{{ route('checkout.form') }}" class="cart-checkout">{{ __('messages.button.continue') }}</a>
                <a href="{{ route('home') }}" class="cart-back">{{ __('messages.button.back_to') }}</a>
            </div>
        </div>
    @endif
</div>

<div class="detail-recommend" style="margin-top: 30px;">
    <h2>{{ __('messages.recommend') }}</h2>
    <div class="detail-recommend-grid">
        @foreach ($rekomendasi as $item)
        <div class="detail-recommend-card">
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
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.quantity-form').forEach(function(form) {
        const input = form.querySelector('input[name="quantity"]');

        form.querySelector('.btn-increase').addEventListener('click', function() {
            input.value = parseInt(input.value) + 1;
            form.submit();
        });

        form.querySelector('.btn-decrease').addEventListener('click', function() {
            let currentValue = parseInt(input.value);
            if (currentValue > 0) {
                input.value = currentValue - 1;
                form.submit();
            }
        });
    });
});
</script>
@endpush