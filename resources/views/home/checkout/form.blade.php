@extends('home.layouts.app')

@section('content')
<div class="checkout-container">
    {{-- Bagian Form --}}
    <div class="checkout-form">
        <form id="checkoutForm" action="{{ route('checkout.save') }}" method="POST">
            @csrf
            {{-- Hidden untuk data voucher & ekspedisi --}}
            <input type="hidden" name="voucher_id" id="voucherHidden">
            <input type="hidden" name="ekspedisi_id" id="ekspedisiHidden">

            {{-- Bagian Kontak --}}
            <div class="checkout-kontak">
                <h3 class="checkout-kontak-title">{{ __('messages.title.contact') }}</h3>
                <input class="checkout-kontak-data" type="email" name="email" placeholder="Email" required>
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
                <div class="checkout-newsletter">
                    <label>
                        <input type="checkbox" name="newsletter" value="yes" required>
                        {{ __('messages.label.email') }}
                    </label>
                </div>
            </div>

            {{-- Bagian Pengantaran --}}
            <div class="checkout-pengantaran">
                <h3 class="checkout-pengantaran-title">{{ __('messages.title.delivery') }}</h3>
                <input class="checkout-pengantaran-data" type="text" name="nama" placeholder="{{ __('messages.placeholder.name') }}" required>

                {{-- Telepon --}}
                <div class="checkout-pengantaran-telepon">
                    <span class="prefix">+62</span>
                    <input class="checkout-pengantaran-data telepon-input" 
                        type="tel" 
                        id="telepon_user" 
                        placeholder="813xxxxxxx" 
                        pattern="[0-9]{8,15}" 
                        required>
                </div>
                <input type="hidden" name="telepon" id="telepon">

                {{-- Alamat Lengkap --}}
                <select style="margin-left: 0px" id="provinsi" name="provinsi">
                    <option value="">Select Provinsi</option>
                </select>
                <select style="margin-left: 0px" id="kota" name="kota" disabled>
                    <option value="">Select Kota/Kabupaten</option>
                </select>
                <select style="margin-left: 0px" id="kecamatan" name="kecamatan" disabled>
                    <option value="">Select Kecamatan</option>
                </select>
                <select style="margin-left: 0px" id="desa" name="desa" disabled>
                    <option value="">Select Kelurahan/Desa</option>
                </select>
                <input class="checkout-pengantaran-data" type="text" name="alamat" placeholder="{{ __('messages.placeholder.address') }}" required>
            </div>

            {{-- Bagian Metode Pembayaran --}}
            <div class="checkout-payment-section">
                <h3 class="checkout-payment-title">{{ __('messages.title.pay') }}</h3>

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
            {{-- <button type="submit" class="checkout-payment-btn">{{ __('messages.button.order') }}</button> --}}
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
                    @if (!empty($item['diskon']))
                        <span class="detail-price-discounted">IDR {{ number_format($item['harga'], 0, ',', '.') }}</span>
                        <span class="detail-price-now">IDR {{ number_format($item["harga_diskon"], 0, ',', '.') }}</span>
                    @else
                        <span class="detail-price-now">IDR {{ number_format($item['harga'], 0, ',', '.') }}</span>
                    @endif
                </p>
            </div>
        @empty
            <p>Keranjang belanja Anda kosong.</p>
        @endforelse

        {{-- Bagian Voucher --}}
        <div class="checkout-voucher">
            <h5>{{ __('messages.title.promo') }}</h5>
            <select id="voucherSelect" class="form-control">
                <option value="">{{ __('messages.button.voucher') }}</option>
                @foreach($voucher as $vouchers)
                    <option 
                        value="{{ $vouchers->voucher_id }}" data-tipe="{{ $vouchers->tipe_diskon }}" data-nilai="{{ $vouchers->nilai_diskon }}" data-min="{{ $vouchers->min_transaksi }}">
                        {{ $vouchers->kode_voucher }}
                    </option>
                @endforeach
            </select>
            <i class="fa-solid fa-chevron-down select-arrow"></i>
        </div>

        <div class="checkout-ekspedisi-section">
            <div class="checkout-ekspedisi-group">
                {{-- Tombol utama --}}
                <button type="button" 
                        class="checkout-ekspedisi-toggle" 
                        data-target="ekspedisi-list">
                    {{ __('messages.button.expedition') }}
                    <span style="float: right; font-size: 14px;">&#9662;</span>
                </button>

                {{-- Daftar ekspedisi, default hidden --}}
                <div id="ekspedisi-list" class="checkout-ekspedisi-list">
                    @foreach($ekspedisi as $item)
                        <div class="checkout-ekspedisi-method">
                            <input type="radio" 
                                id="ekspedisi-{{ $item->ekspedisi_id }}" 
                                name="ekspedisi_id" 
                                value="{{ $item->ekspedisi_id }}" 
                                required>

                            <label for="ekspedisi-{{ $item->ekspedisi_id }}">
                                @if($item->icon)
                                    <img src="{{ route('storage', ['folder' => 'icons', 'filename' => $item->icon]) }}" 
                                        alt="{{ $item->nama_ekspedisi }}">
                                @endif
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Bagian Total --}}
        {{-- <div class="checkout-total-section">
            <div class="checkout-subtotal">
                <span>Subtotal {{ count($cart) }} item</span>
            </div>
            <div class="checkout-total">
                <span>Total</span>
                <span class="checkout-total-price">IDR {{ number_format($total, 0, ',', '.') }}</span>
            </div>
        </div> --}}

        {{-- Bagian Total --}}
        <div class="checkout-total-section">
            <div class="checkout-subtotal">
                <span>Subtotal</span>
                <span class="checkout-subtotal-price">{{ count($cart) }} item</span>
            </div>

            <div class="checkout-subtotal">
                <span>Subtotal Pesanan</span>
                <span class="checkout-subtotal-price">IDR {{ number_format($total, 0, ',', '.') }}</span>
            </div>

            <div class="checkout-voucher-discount" style="display: none;">
                <span>Potongan Voucher</span>
                <span class="checkout-voucher-price text-danger">- IDR 0</span>
            </div>

            <div class="checkout-total">
                <span>Total Pembayaran</span>
                <span class="checkout-total-price">IDR {{ number_format($total, 0, ',', '.') }}</span>
            </div>
        </div>

        <button type="submit" form="checkoutForm" class="checkout-payment-btn">{{ __('messages.button.order') }}</button>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[action="{{ route('checkout.save') }}"]');
    const teleponUserInput = document.getElementById('telepon_user');
    const teleponHiddenInput = document.getElementById('telepon');

    function showToast(icon, title) {
        Swal.fire({
            toast: true, 
            position: 'top-end', 
            icon: icon,
            title: title,
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            customClass: {
                popup: 'swal2-border-radius',
            }
        });
    }

    // Simpan voucher dan ekspedisi ke hidden input
    const voucherSelect = document.getElementById("voucherSelect");
    const voucherHidden = document.getElementById("voucherHidden");
    const ekspedisiHidden = document.getElementById("ekspedisiHidden");

    if (voucherSelect && voucherHidden) {
        const totalText = document.querySelector(".checkout-total-price");
        const baseTotal = {{ $total }};

        // update hidden saat load pertama
        voucherHidden.value = voucherSelect.value;

        voucherSelect.addEventListener("change", function() {
    const selected = this.options[this.selectedIndex];
    const tipe = selected.dataset.tipe;
    const nilai = parseFloat(selected.dataset.nilai || 0);
    const min = parseFloat(selected.dataset.min || 0);

    const subtotalText = document.querySelector(".checkout-subtotal-price");
    const voucherRow = document.querySelector(".checkout-voucher-discount");
    const voucherPrice = document.querySelector(".checkout-voucher-price");
    const totalText = document.querySelector(".checkout-total-price");

    let newTotal = baseTotal;
    let potongan = 0;

    if (this.value) {
        if (baseTotal >= min) {
            if (tipe === "persen") {
                potongan = (nilai / 100) * baseTotal;
            } else if (tipe === "nominal") {
                potongan = nilai;
            }

            newTotal = baseTotal - potongan;
            voucherHidden.value = this.value;

            // Tampilkan baris potongan voucher
            voucherRow.style.display = "flex";
            voucherPrice.textContent = "- IDR " + potongan.toLocaleString("id-ID");

            Swal.fire({
                toast: true,
                position: "top-end",
                icon: "success",
                title: `Voucher diterapkan! Hemat IDR ${potongan.toLocaleString("id-ID")}`,
                showConfirmButton: false,
                timer: 2500
            });
        } else {
            Swal.fire({
                toast: true,
                position: "top-end",
                icon: "warning",
                title: "Belanja Anda belum memenuhi syarat minimal voucher",
                showConfirmButton: false,
                timer: 2500
            });
            this.value = "";
            voucherHidden.value = "";
            voucherRow.style.display = "none";
        }
    } else {
        // Reset jika tidak pakai voucher
        voucherHidden.value = "";
        voucherRow.style.display = "none";
        potongan = 0;
        newTotal = baseTotal;
    }

    subtotalText.textContent = "IDR " + baseTotal.toLocaleString("id-ID");
    totalText.textContent = "IDR " + newTotal.toLocaleString("id-ID");
});

    }

    // Update ekspedisi ke hidden
    document.querySelectorAll('input[name="ekspedisi_id"]').forEach(radio => {
        radio.addEventListener("change", function() {
            ekspedisiHidden.value = this.value;
        });
    });

    // Validasi form
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        voucherHidden.value = voucherSelect.value;
        const ekspedisiTerpilih = form.querySelector('input[name="ekspedisi_id"]:checked');
        if (ekspedisiTerpilih) {
            ekspedisiHidden.value = ekspedisiTerpilih.value;
        }

        const teleponUser = teleponUserInput.value.trim();
        const email = form.querySelector('input[name="email"]').value.trim();
        const metodeTerpilih = form.querySelector('input[name="metode_pembayaran_id"]:checked');
        const cartItems = document.querySelectorAll('.checkout-product-item');

        if (!teleponUser) {
            showToast('error', 'Phone number is required!');
            return;
        }
        if (!/^\d{8,15}$/.test(teleponUser)) {
            showToast('error', 'Invalid phone number (8-15 digits)!');
            return;
        }
        teleponHiddenInput.value = '+62' + teleponUser;

        if (!/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/.test(email)) {
            showToast('error', 'Invalid email format!');
            return;
        }

        if (!metodeTerpilih) {
            showToast('error', 'Please select a payment method!');
            return;
        }

        if (cartItems.length === 0) {
            showToast('error', 'Empty shopping cart!');
            return;
        }

        showToast('success', 'Form valid, Transaction processed! Please check your email.');
        setTimeout(() => form.submit(), 500);
    });

    teleponUserInput.addEventListener('input', function() {
        this.value = this.value.replace(/[^\d]/g, '');
    });

    // Toggle payment list
    document.querySelectorAll('.checkout-payment-toggle').forEach(button => {
        button.addEventListener('click', () => {
            const target = document.getElementById(button.dataset.target);
            document.querySelectorAll('.checkout-payment-list').forEach(list => list.classList.remove('show'));
            target.classList.toggle('show');
        });
    });

    // Toggle expedition list
    document.querySelectorAll('.checkout-ekspedisi-toggle').forEach(button => {
        button.addEventListener('click', () => {
            const target = document.getElementById(button.dataset.target);
            document.querySelectorAll('.checkout-ekspedisi-list').forEach(list => list.classList.remove('show'));
            target.classList.toggle('show');
        });
    });

    // --- Load wilayah ---
    const provinsi = document.getElementById("provinsi");
    const kota = document.getElementById("kota");
    const kecamatan = document.getElementById("kecamatan");
    const desa = document.getElementById("desa");

    // Inisialisasi Select2
    $("#provinsi, #kota, #kecamatan, #desa").select2({ width: '100%' });

    // Fetch Provinsi
    fetch("/wilayah/provinsi")
        .then(res => res.json())
        .then(data => {
            data.forEach(item => {
                provinsi.innerHTML += `<option value="${item.name}" data-id="${item.id}">${item.name}</option>`;
            });
            $("#provinsi").trigger("change.select2");
        });

    // Provinsi → Kota
    $("#provinsi").on("change", function () {
        const provinsiId = $(this).find(':selected').data('id');

        kota.innerHTML = `<option value="">Select Kota/Kabupaten</option>`;
        kecamatan.innerHTML = `<option value="">Select Kecamatan</option>`;
        desa.innerHTML = `<option value="">Select Kelurahan/Desa</option>`;

        $("#kota, #kecamatan, #desa").val(null).trigger("change.select2").prop("disabled", true);

        if (provinsiId) {
            fetch(`/wilayah/kota/${provinsiId}`)
                .then(res => res.json())
                .then(data => {
                    data.forEach(item => {
                        kota.innerHTML += `<option value="${item.name}" data-id="${item.id}">${item.name}</option>`;
                    });
                    $("#kota").prop("disabled", false).trigger("change.select2");
                });
        }
    });

    // Kota → Kecamatan
    $("#kota").on("change", function () {
        const kotaId = $(this).find(':selected').data('id');

        kecamatan.innerHTML = `<option value="">Select Kecamatan</option>`;
        desa.innerHTML = `<option value="">Select Kelurahan/Desa</option>`;

        $("#kecamatan, #desa").val(null).trigger("change.select2").prop("disabled", true);

        if (kotaId) {
            fetch(`/wilayah/kecamatan/${kotaId}`)
                .then(res => res.json())
                .then(data => {
                    data.forEach(item => {
                        kecamatan.innerHTML += `<option value="${item.name}" data-id="${item.id}">${item.name}</option>`;
                    });
                    $("#kecamatan").prop("disabled", false).trigger("change.select2");
                });
        }
    });

    // Kecamatan → Desa
    $("#kecamatan").on("change", function () {
        const kecamatanId = $(this).find(':selected').data('id');

        desa.innerHTML = `<option value="">Select Kelurahan/Desa</option>`;
        $("#desa").val(null).trigger("change.select2").prop("disabled", true);

        if (kecamatanId) {
            fetch(`/wilayah/desa/${kecamatanId}`)
                .then(res => res.json())
                .then(data => {
                    data.forEach(item => {
                        desa.innerHTML += `<option value="${item.name}" data-id="${item.id}">${item.name}</option>`;
                    });
                    $("#desa").prop("disabled", false).trigger("change.select2");
                });
        }
    });
});
</script>
@endpush
