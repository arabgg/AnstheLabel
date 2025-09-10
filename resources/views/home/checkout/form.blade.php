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
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
                <div class="checkout-newsletter">
                    <label>
                        <input type="checkbox" name="newsletter" value="yes" required>
                        Email me news and offers
                    </label>
                </div>
            </div>

            {{-- Bagian Pengantaran --}}
            <div class="checkout-pengantaran">
                <h3 class="checkout-pengantaran-title">Pengantaran</h3>
                <input class="checkout-pengantaran-data" type="text" name="nama" placeholder="Nama Lengkap" required>

                {{-- Telepon --}}
                <div class="checkout-pengantaran-telepon">
                    <span class="prefix">+62</span>
                    <input class="checkout-pengantaran-data telepon-input" 
                        type="tel" 
                        id="telepon_user" 
                        placeholder="81234567890" 
                        pattern="[0-9]{8,15}" 
                        required>
                </div>
                <input type="hidden" name="telepon" id="telepon">

                {{-- Alamat Lengkap --}}
                <select class="checkout-pengantaran-data" style="margin-left: 0px" id="provinsi" name="provinsi" class="form-select">
                    <option value="">Pilih Provinsi</option>
                </select>
                <select class="checkout-pengantaran-data" style="margin-left: 0px" id="kota" name="kota" class="form-select" disabled>
                    <option value="">Pilih Kota/Kabupaten</option>
                </select>
                <select class="checkout-pengantaran-data" style="margin-left: 0px" id="kecamatan" name="kecamatan" class="form-select" disabled>
                    <option value="">Pilih Kecamatan</option>
                </select>
                <select class="checkout-pengantaran-data" style="margin-left: 0px" id="desa" name="desa" class="form-select" disabled>
                    <option value="">Pilih Kelurahan/Desa</option>
                </select>
                <input class="checkout-pengantaran-data" type="text" name="alamat" placeholder="Alamat" required>
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
                            <span style="float: right; font-size: 14px;">&#9662;</span>
                        </button>

                        <div id="payment-{{ $grouped->first()->metode_id }}" class="checkout-payment-list">
                            @foreach($grouped as $method)
                                <div class="checkout-payment-method">
                                    <input type="radio" 
                                        id="metode-{{ $method->metode_pembayaran_id }}" 
                                        name="metode_pembayaran_id" 
                                        value="{{ $method->metode_pembayaran_id }}" 
                                        required>
                                    <label for="metode-{{ $method->metode_pembayaran_id }}">
                                        @if($method->icon)
                                            <img src="{{ route('storage', ['folder' => 'icons', 'filename' => $method->icon]) }}" 
                                                alt="{{ $method->nama_pembayaran }}">
                                        @else
                                            <span>{{ $method->nama_pembayaran }}</span>
                                        @endif
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            <button type="submit" class="checkout-payment-btn">Buat Transaksi</button>
        </form>
    </div>

    {{-- Bagian Ringkasan --}}
    <div class="checkout-summary">
        @forelse ($cart as $item)
            <div class="checkout-product-item">
                <img src="{{ $item['foto'] ? route('storage', ['folder' => 'foto_produk', 'filename' => $item['foto']]) : 'https://via.placeholder.com/80' }}" alt="{{ $item['nama'] }}">
                <div class="checkout-product-info">
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
document.querySelector('form[action="{{ route('checkout.save') }}"]').addEventListener('submit', function(e){
    const teleponUser = document.getElementById('telepon_user').value.trim();
    const teleponHidden = document.getElementById('telepon');

    if(!teleponUser) {
        e.preventDefault();
        Swal.fire({ icon: 'error', title: 'Nomor telepon harus diisi!' });
        return;
    }
    teleponHidden.value = '+62' + teleponUser;
});

document.addEventListener("DOMContentLoaded", function () {
    const provinsi = document.getElementById("provinsi");
    const kota = document.getElementById("kota");
    const kecamatan = document.getElementById("kecamatan");
    const desa = document.getElementById("desa");

    // Load provinsi
    fetch("/wilayah/provinsi")
        .then(res => res.json())
        .then(data => {
            data.forEach(item => {
                provinsi.innerHTML += `<option value="${item.name}" data-id="${item.id}">${item.name}</option>`;
            });
        });

    // Event provinsi -> kota
    provinsi.addEventListener("change", function () {
        kota.innerHTML = `<option value="">Pilih Kota</option>`;
        kecamatan.innerHTML = `<option value="">Pilih Kecamatan</option>`;
        desa.innerHTML = `<option value="">Pilih Desa</option>`;

        if (this.value) {
            kota.disabled = false;

            const provinsiId = this.selectedOptions[0].getAttribute("data-id");

            fetch(`/wilayah/kota/${provinsiId}`)
                .then(res => res.json())
                .then(data => {
                    data.forEach(item => {
                        kota.innerHTML += `<option value="${item.name}" data-id="${item.id}">${item.name}</option>`;
                    });
                });
        } else {
            kota.disabled = true;
            kecamatan.disabled = true;
            desa.disabled = true;
        }
    });

    // Event kota -> kecamatan
    kota.addEventListener("change", function () {
        kecamatan.innerHTML = `<option value="">Pilih Kecamatan</option>`;
        desa.innerHTML = `<option value="">Pilih Desa</option>`;

        if (this.value) {
            kecamatan.disabled = false;

            const kotaId = this.selectedOptions[0].getAttribute("data-id");

            fetch(`/wilayah/kecamatan/${kotaId}`)
                .then(res => res.json())
                .then(data => {
                    data.forEach(item => {
                        kecamatan.innerHTML += `<option value="${item.name}" data-id="${item.id}">${item.name}</option>`;
                    });
                });
        } else {
            kecamatan.disabled = true;
            desa.disabled = true;
        }
    });

    // Event kecamatan -> desa
    kecamatan.addEventListener("change", function () {
        desa.innerHTML = `<option value="">Pilih Desa</option>`;

        if (this.value) {
            desa.disabled = false;

            const kecamatanId = this.selectedOptions[0].getAttribute("data-id");

            fetch(`/wilayah/desa/${kecamatanId}`)
                .then(res => res.json())
                .then(data => {
                    data.forEach(item => {
                        desa.innerHTML += `<option value="${item.name}" data-id="${item.id}">${item.name}</option>`;
                    });
                });
        } else {
            desa.disabled = true;
        }
    });
});
</script>

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

// Toggle payment list
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

// Validasi checkout secara dinamis
document.querySelector('form[action="{{ route('checkout.save') }}"]').addEventListener('submit', function(e){
    e.preventDefault(); // hentikan sementara submit

    const form = e.target;

    // Cek metode pembayaran terpilih
    const metodeTerpilih = form.querySelector('input[name="metode_pembayaran_id"]:checked');
    if (!metodeTerpilih) {
        showToast('error', 'Silakan pilih metode pembayaran!');
        return;
    }

    // Cek jumlah item di keranjang melalui DOM
    const cartItems = document.querySelectorAll('.checkout-product-item');
    if (cartItems.length === 0) {
        showToast('error', 'Keranjang belanja kosong!');
        return;
    }

    // Jika valid, tampilkan toast sukses
    showToast('success', 'Metode pembayaran berhasil dipilih!');

    // Submit form setelah delay agar toast terlihat
    setTimeout(() => {
        form.submit();
    }, 500); // 0.5 detik
});
</script>
@endpush
