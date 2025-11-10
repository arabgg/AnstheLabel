@extends('home.layouts.app')

@section('content')
<div class="custom-transaksi-banner">
    <div class="skeleton-wrapper hero-collection-skeleton">
        <div class="skeleton skeleton-img"></div>
    </div>
    <div class="custom-transaksi-banner skeleton-target" style="display:none;">    
        <video class="transaksi-banner" autoplay muted loop>
            <source src="{{ route('storage', ['folder' => 'banner', 'filename' => $hero->foto_banner]) }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>
</div>


<div class="transaksi-container">
    <!-- Progress Bar -->
    <div class="transaksi-progress {{ $transaksi->status_transaksi === 'batal' ? 'is-batal' : '' }}">
        @if($transaksi->status_transaksi !== 'batal')
            <div class="transaksi-progress-bar" 
                 style="width: calc({{ $stepIndex }} / ({{ count($steps) - 2 }}) * 75%)"></div>
        @else
            <div class="transaksi-progress-bar batal" style="width: 75%"></div>
        @endif

        {{-- Render step awal --}}
        @foreach($steps as $key => $step)
            @if(!in_array($key, ['selesai','batal']))
                <div class="transaksi-step {{ $loop->index <= $stepIndex && $transaksi->status_transaksi !== 'batal' ? 'active' : '' }}">
                    <div class="transaksi-step-icon"><i class="{{ $step['icon'] }}"></i></div>
                    <p class="transaksi-step-title">{{ $step['label'] }}</p>
                    <p class="transaksi-step-desc">{{ $step['desc'] }}</p>
                </div>
            @endif
        @endforeach

        {{-- Step terakhir --}}
        @if($transaksi->status_transaksi === 'batal')
            <div class="transaksi-step batal active">
                <div class="transaksi-step-icon"><i class="{{ $steps['batal']['icon'] }}"></i></div>
                <p class="transaksi-step-title">{{ $steps['batal']['label'] }}</p>
                <p class="transaksi-step-desc">{{ $steps['batal']['desc'] }}</p>
            </div>
        @else
            <div class="transaksi-step {{ $transaksi->status_transaksi === 'selesai' ? 'active' : '' }}">
                <div class="transaksi-step-icon"><i class="{{ $steps['selesai']['icon'] }}"></i></div>
                <p class="transaksi-step-title">{{ $steps['selesai']['label'] }}</p>
                <p class="transaksi-step-desc">{{ $steps['selesai']['desc'] }}</p>
            </div>
        @endif
    </div>

    <div class="transaksi-content">
        <!-- Box Detail Pesanan -->
        <div class="transaksi-left">
            <div class="transaksi-card">
                {{-- Kiri: Detail Pesanan --}}
                <div class="transaksi-detail-left">
                    <div class="transaksi-detail-order">
                        <h4>{{ __('messages.detail.order') }}</h4>
                        <p>{{ __('messages.detail.contact') }}<br>
                            {{ $transaksi->email }}
                            {{ $transaksi->no_telp }}
                        </p>
                    </div>
                    <div class="transaksi-detail-address">
                        <h4>{{ __('messages.detail.shipping') }}</h4>
                        <p>
                            {{ $transaksi->nama_customer }}<br>
                            {{ $transaksi->alamat }}<br>
                        </p>
                    </div>
                </div>

                {{-- Kanan: Invoice + Metode Pembayaran --}}
                <div class="transaksi-detail-right">
                    <div class="transaksi-invoice">
                        <h4>{{ __('messages.detail.code') }}</h4>
                        <p class="invoice">
                            <button class="invoice-copy-btn" onclick="copyToClipboard('invoiceCode')">
                                <i class="fa-regular fa-clipboard"></i>
                            </button>
                            <span class="invoice-kode" id="invoiceCode">{{ $transaksi->kode_invoice }}</span><br>
                            <small class="invoice-note">{{ __('messages.detail.note') }}</small>
                        </p>
                    </div>
                    <div class="transaksi-metode-pembayaran">
                        <h4>{{ __('messages.detail.pay') }}</h4>
                        <div class="transaksi-metode">
                            <img src="{{ route('storage', ['folder' => 'icons', 'filename' => $transaksi->pembayaran->metode->icon]) }}" alt="Metode Logo" class="metode-logo">
                            <p>
                                {{ $transaksi->pembayaran->metode->atas_nama }}<br>
                                <button class="metode-copy-btn" onclick="copyToClipboard('metodeCode')">
                                    <i class="fa-regular fa-clipboard "></i>
                                </button>
                                <span class="metode-kode" id="metodeCode">{{ $transaksi->pembayaran->metode->kode_bayar }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="transaksi-status">
                <div class="transaksi-status-pembayaran">
                    <p>{{ __('messages.status.pay') }}</p>
                    <span class="status-pembayaran {{ Str::slug($transaksi->pembayaran->status_pembayaran) }}">
                        {{ $transaksi->pembayaran->status_pembayaran }}
                    </span>
                </div>
                <div class="transaksi-status-transaksi">
                    <p>{{ __('messages.status.transaction') }}</p>
                    <span class="status-transaksi {{ Str::slug($transaksi->status_transaksi) }}">
                        {{ $transaksi->status_transaksi }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Box Ringkasan Order -->
        <div class="transaksi-right">
            @foreach($transaksi->detail as $item)
                <div class="transaksi-item">
                    {{-- Foto produk --}}
                    <img src="{{ route('storage', ['folder' => 'foto_produk', 'filename' => $item->produk->fotoUtama->foto_produk]) }}" 
                        alt="{{ optional($item->produk)->nama_produk ?? 'Produk' }}">

                    {{-- Informasi produk --}}
                    <div class="transaksi-item-info">
                        <p class="transaksi-item-nama">{{ ($item->produk)->nama_produk ?? 'Produk tidak ditemukan' }}</p>
                        <p>Color: {{ ($item->warna)->nama_warna ?? '-' }}</p>
                        <p>Size: {{ ($item->ukuran)->nama_ukuran ?? '-' }}</p>
                        <p>Amount: {{ $item->jumlah }}</p>
                    </div>

                    {{-- Harga --}}
                    <div class="transaksi-item-harga">
                        @if (!empty($item->produk->diskon))
                            <span class="detail-price-discounted">IDR {{ number_format($item->produk->harga * $item->jumlah, 0, ',', '.') }}</span>
                            <span class="detail-price-now">IDR {{ number_format(($item->produk->harga - $item->produk->diskon) * $item->jumlah, 0, ',', '.') }}</span>
                        @else
                            <span class="detail-price-now">IDR {{ number_format($item->produk->harga * $item->jumlah, 0, ',', '.') }}</span>
                        @endif
                    </div>
                </div>
            @endforeach

            {{-- Ringkasan Transaksi --}}
            <div class="transaksi-ringkasan">
                <div class="transaksi-subtotal">
                    <span>Subtotal</span>
                    <span>{{ $transaksi->detail->sum('jumlah') }} item</span>
                </div>
                <div class="transaksi-subtotal">
                    <span>Subtotal Pesanan</span>
                    <span>IDR {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                @if (!empty($transaksi->pembayaran->voucher) && !empty($transaksi->pembayaran->voucher->nilai_diskon))
                    <div class="transaksi-subtotal">
                        <span>Potongan Voucher</span>
                        <span>- IDR {{ number_format($transaksi->pembayaran->voucher->nilai_diskon, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="transaksi-total">
                    <span>Total Pembayaran</span>
                    <span>IDR {{ number_format($transaksi->pembayaran->total_harga, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Upload Bukti Pembayaran -->
            <div class="transaksi-upload">
                @if(!$transaksi->pembayaran->bukti_pembayaran)
                <div class="upload-bukti">
                    <h4>{{ __('messages.status.confirm') }}</h4>
                    <form action="{{ route('transaksi.upload', $transaksi->pembayaran->pembayaran_id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- reCAPTCHA dulu --}}
                        <div class="captcha-container mb-4">
                            {!! NoCaptcha::display(['data-callback' => 'enableUploadForm']) !!}
                        </div>
                        {!! NoCaptcha::renderJs() !!}

                        <div id="uploadSection" style="display: none;">
                            <input type="file" name="bukti_pembayaran" accept="image/*,application/pdf" required>
                            <button type="submit" class="btn-upload">{{ __('messages.button.upload') }}</button>
                        </div>
                    </form>
                </div>
                @else
                <div class="bukti-terbayar">
                    <h4>{{ __('messages.status.valid') }}</h4>
                    <img src="{{ route('storage', ['folder' => 'bukti', 'filename' => $transaksi->pembayaran->bukti_pembayaran]) }}" alt="Bukti Pemabayaran" class="">
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function copyToClipboard(elementId) {
        var text = document.getElementById(elementId).innerText;
        navigator.clipboard.writeText(text).then(() => {
            Swal.fire({
                toast: true,               
                position: 'top-end',       
                icon: 'success',
                title: 'Copied successfully!',
                text: text,                
                showConfirmButton: false,
                timer: 2000,               
                timerProgressBar: true
            });
        });
    }

    @if(session('success'))
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Upload Successful',
            showConfirmButton: false,
            timer: 2500,
            timerProgressBar: true
        });
    @endif

    @if(session('error'))
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: 'Upload Failed',
            showConfirmButton: false,
            timer: 2500,
            timerProgressBar: true
        });
    @endif

    function enableUploadForm() {
        document.getElementById('uploadSection').style.display = 'block';

        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Captcha berhasil diverifikasi!',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true
        });
    }
</script>
@endpush