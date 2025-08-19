@extends('home.layouts.app')

@section('content')
<div class="payment-container">
    <h2 class="text-xl font-bold mb-4">Konfirmasi Pembayaran</h2>

    {{-- Data Diri --}}
    <div class="mb-6 border p-4 rounded bg-gray-50">
        <h3 class="font-semibold mb-2">Data Diri</h3>
        <p><strong>Nama:</strong> {{ $checkoutData['nama'] }}</p>
        <p><strong>Email:</strong> {{ $checkoutData['email'] }}</p>
        <p><strong>Telepon:</strong> {{ $checkoutData['telepon'] }}</p>
        <p><strong>Alamat:</strong> {{ $checkoutData['alamat'] }}</p>
    </div>

    {{-- Ringkasan Belanja --}}
    <div class="mb-6 border p-4 rounded bg-gray-50">
        <h3 class="font-semibold mb-2">Ringkasan Belanja</h3>
        @foreach($cart as $item)
            <div class="flex justify-between items-center border-b py-2">
                <div class="flex items-center gap-3">
                    <img src="{{ $item['foto'] ? asset('storage/foto_produk/' . $item['foto']) : 'https://via.placeholder.com/60' }}"
                         alt="{{ $item['nama'] }}"
                         class="w-16 h-16 object-cover rounded">
                    <div>
                        <p class="font-medium">{{ $item['nama'] }}</p>
                        <p class="text-sm text-gray-600">Warna: {{ $item['warna_nama'] ?? '-' }}</p>
                        <p class="text-sm text-gray-600">Ukuran: {{ $item['ukuran_nama'] ?? '-' }}</p>
                        <p class="text-sm">Qty: {{ $item['quantity'] }}</p>
                    </div>
                </div>
                <p class="font-semibold">Rp {{ number_format($item['harga'] * $item['quantity'], 0, ',', '.') }}</p>
            </div>
        @endforeach

        <div class="flex justify-between mt-3 font-semibold">
            <span>Total</span>
            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
        </div>
    </div>

    {{-- Form Pembayaran --}}
    <form action="{{ route('checkout.process') }}" method="POST" class="border p-4 rounded bg-gray-50">
        @csrf
        <h3 class="font-semibold mb-2">Pilih Metode Pembayaran</h3>
        
        @foreach($paymentMethods->get() as $method)
            <div class="mb-2">
                <label class="flex items-center gap-2">
                    <input type="radio" name="metode_id" value="{{ $method->metode_id }}" required>
                    <span>{{ $method->nama_metode }}</span>
                </label>
            </div>
        @endforeach

        @error('metode_id')
            <p class="text-red-500 text-sm">{{ $message }}</p>
        @enderror

        <button type="submit" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded">
            Proses Pembayaran
        </button>
    </form>
</div>
@endsection
