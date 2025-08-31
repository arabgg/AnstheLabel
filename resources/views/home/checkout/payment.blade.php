@extends('home.layouts.app')

@section('content')
<div class="payment-container">
    <h2 class="payment-title">Konfirmasi Pembayaran</h2>

    <div class="payment-content">
        {{-- Data Diri --}}
        <div class="payment-section payment-user-info">
            <h3 class="payment-section-title">Data Diri</h3>
            <p><strong>Nama:</strong> {{ $checkoutData['nama'] }}</p>
            <p><strong>Email:</strong> {{ $checkoutData['email'] }}</p>
            <p><strong>Telepon:</strong> {{ $checkoutData['telepon'] }}</p>
            <p><strong>Alamat:</strong> {{ $checkoutData['alamat'] }}</p>
        </div>

        {{-- Form Pembayaran --}}
        <div class="payment-section payment-form-wrapper">
            <form id="checkoutForm" action="{{ route('checkout.process') }}" method="POST" class="payment-form">
                @csrf
                {{-- input pilihan pembayaran --}}
                @foreach($paymentMethods as $method)
                    <div class="payment-method">
                        <label>
                            <input type="radio" name="metode_id" value="{{ $method->metode_id }}" required>
                            <span>{{ $method->nama_metode }}</span>
                        </label>
                    </div>
                @endforeach
            </form>
        </div>
    </div>

    {{-- tombol di luar form --}}
    <div class="payment-btn-wrapper">
        <button type="submit" form="checkoutForm" class="payment-btn">
            Proses Pembayaran
        </button>
    </div>

    {{-- Ringkasan Belanja --}}
    <div class="payment-section">
        <h3 class="payment-section-title">Ringkasan Belanja</h3>
        @foreach($cart as $item)
            <div class="payment-item">
                <div class="payment-item-left">
                    <img src="{{ $item['foto'] ? asset('storage/foto_produk/' . $item['foto']) : 'https://via.placeholder.com/60' }}"
                         alt="{{ $item['nama'] }}"
                         class="payment-item-img">
                    <div class="payment-item-info">
                        <p class="payment-item-name">{{ $item['nama'] }}</p>
                        <p class="payment-item-detail">Warna: {{ $item['warna_nama'] ?? '-' }}</p>
                        <p class="payment-item-detail">Ukuran: {{ $item['ukuran_nama'] ?? '-' }}</p>
                        <p class="payment-item-detail">Qty: {{ $item['quantity'] }}</p>
                    </div>
                </div>
                <p class="payment-item-price">Rp {{ number_format($item['harga'] * $item['quantity'], 0, ',', '.') }}</p>
            </div>
        @endforeach

        <div class="payment-total">
            <span>Total</span>
            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
        </div>
    </div>
</div>
@endsection
