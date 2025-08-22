@extends('home.layouts.app')

@section('content')
<div class="checkout-container">
    {{-- Bagian Form --}}
    <div class="checkout-form">
        <form action="{{ route('checkout.save') }}" method="POST">
            @csrf
            <div class="checkout-kontak">
                <h3 class="checkout-kontak-title">Kontak</h3>
                <input class="checkout-kontak-data" type="email" name="email" placeholder="Email" required>
                <div class="checkout-newsletter">
                    <label>
                        <input type="radio" name="newsletter" value="yes" required>
                        Email me news and offers
                    </label>
                </div>
            </div>

            <div class="checkout-pengantaran">
                <h3 class="checkout-pengantaran-title">Pengantaran</h3>
                <input class="checkout-pengantaran-data" type="text" name="nama" placeholder="Nama Lengkap" required>
                <input class="checkout-pengantaran-data" type="text" name="alamat" placeholder="Alamat" required>
                <input class="checkout-pengantaran-data" type="text" name="kota" placeholder="Kota" required>
                <input class="checkout-pengantaran-data" type="text" name="kecamatan" placeholder="Kecamatan" required>
                <input class="checkout-pengantaran-data" type="text" name="telepon" placeholder="Telepon" required>
            </div>

            <button type="submit" class="checkout-payment-btn">Payment</button>
        </form>
    </div>

    {{-- Bagian Ringkasan --}}
    <div class="checkout-summary">
        @php
            $cart = session('cart', []);
            $subtotal = 0;
        @endphp

        @forelse ($cart as $item)
            @php
                $itemTotal = $item['harga'] * $item['quantity'];
                $subtotal += $itemTotal;
            @endphp
            <div class="checkout-product-item">
                <img src="{{ $item['foto'] ? asset('storage/foto_produk/' . $item['foto']) : 'https://via.placeholder.com/80' }}" alt="{{ $item['nama'] }}">
                <div>
                    <h3 class="checkout-product-name">{{ $item['nama'] }}</h3>
                    <p>Warna : {{ $item['warna_nama'] ?? '-' }}</p>
                    <p>Ukuran : {{ $item['ukuran_nama'] ?? '-' }}</p>
                    <p>Qty : {{ $item['quantity'] }}</p>
                </div>
                <p class="checkout-product-price">Rp {{ number_format($itemTotal, 0, ',', '.') }}</p>
            </div>
        @empty
            <p>Keranjang belanja Anda kosong.</p>
        @endforelse
        
        <div class="checkout-total-section">
            <div class="checkout-subtotal">
                <span>Subtotal {{ count($cart) }} item</span>
            </div>
            <div class="checkout-total">
                <span>Total</span>
                <span class="checkout-total-price">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
