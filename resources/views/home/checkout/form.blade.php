    @extends('home.layouts.app')

    @section('content')
    <div class="checkout-container">
        {{-- Bagian Form --}}
        <div class="checkout-form">
            <form action="{{ route('checkout.save') }}" method="POST">
                @csrf
                {{-- Bagian Kontak --}}
                <div class="checkout-kontak">
                    <h3 class="checkout-kontak-title">Kontak</h3>
                    <input class="checkout-kontak-data" type="email" name="email" placeholder="Email" required>
                    <div class="checkout-newsletter">
                        <label>
                            <input type="checkbox" name="newsletter" value="yes">
                            Email me news and offers
                        </label>
                    </div>
                </div>

                {{-- Bagian Pengantaran --}}
                <div class="checkout-pengantaran">
                    <h3 class="checkout-pengantaran-title">Pengantaran</h3>
                    <input class="checkout-pengantaran-data" type="text" name="nama" placeholder="Nama Lengkap" required>
                    <input class="checkout-pengantaran-data" type="text" name="alamat" placeholder="Alamat" required>
                    <input class="checkout-pengantaran-data" type="text" name="kota" placeholder="Kota" required>
                    <input class="checkout-pengantaran-data" type="text" name="kecamatan" placeholder="Kecamatan" required>
                    <input class="checkout-pengantaran-data" type="tel" name="telepon" placeholder="Telepon" pattern="[0-9]+" required>
                </div>

                {{-- Bagian Metode Pembayaran --}}
<div class="checkout-payment-section">
    <h3 class="checkout-payment-title">Metode Pembayaran</h3>

    @foreach($metode->groupBy('metode_id') as $grouped)
        <div class="checkout-payment-group">
            <button type="button" 
                    class="checkout-payment-toggle" 
                    data-target="payment-{{ $grouped->first()->metode_id }}">
                {{ $grouped->first()->metode->nama_metode }}
            </button>

            <div id="payment-{{ $grouped->first()->metode_id }}" 
                 class="checkout-payment-list">
                @foreach($grouped as $method)
                    <div class="checkout-payment-method">
                        <input type="radio" 
                               id="metode-{{ $method->metode_pembayaran_id }}" 
                               name="metode_pembayaran_id" 
                               value="{{ $method->metode_pembayaran_id }}" required>
                        <label for="metode-{{ $method->metode_pembayaran_id }}">
                            @if($method->icon)
                                <img src="{{ route('storage', ['folder' => 'icons', 'filename' => $method->icon]) }}" 
                                     alt="{{ $method->nama_pembayaran }}">
                            @endif
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>

                <button type="submit" class="checkout-payment-btn">Pilih Metode Pembayaran</button>
            </form>
        </div>

        {{-- Bagian Ringkasan --}}
        <div class="checkout-summary">
            @forelse ($cart as $item)
                <div class="checkout-product-item">
                    <img src="{{ $item['foto'] ? route('storage', ['folder' => 'foto_produk', 'filename' => $item['foto']]) : 'https://via.placeholder.com/80' }}" alt="{{ $item['nama'] }}">
                    <div>
                        <h3 class="checkout-product-name">{{ $item['nama'] }}</h3>
                        <p>Warna : {{ $item['warna_nama'] ?? '-' }}</p>
                        <p>Ukuran : {{ $item['ukuran_nama'] ?? '-' }}</p>
                        <p>Qty : {{ $item['quantity'] }}</p>
                    </div>
                    <p class="checkout-product-price">
                        IDR {{ number_format($item['harga'] * $item['quantity'], 2, ',', '.') }}
                    </p>
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
                    <span class="checkout-total-price">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>
    @endsection

    @push('scripts')
    <script>
    document.querySelectorAll('.checkout-payment-toggle').forEach(button => {
    button.addEventListener('click', () => {
        const target = document.getElementById(button.dataset.target);

        if (target.classList.contains('show')) {
            target.classList.remove('show');
        } else {
            document.querySelectorAll('.checkout-payment-list').forEach(list => list.classList.remove('show'));
            target.classList.add('show');
        }
    });
});

    </script>
    @endpush
