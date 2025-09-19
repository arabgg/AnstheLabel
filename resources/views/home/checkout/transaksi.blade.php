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

    <!-- Timer -->
    {{-- <div class="transaksi-timer">
        <span>2 Hours 59 Minute 59 Second</span>
    </div> --}}

    <div class="transaksi-content">
        <!-- Box Detail Pesanan -->
        <div class="transaksi-left">
            <div class="transaksi-card">
                {{-- Kiri: Detail Pesanan --}}
                <div class="transaksi-detail-left">
                    <h4>Order Details</h4>
                    <p>Contact Information<br>
                        {{ $transaksi->email }}
                        {{ $transaksi->no_telp }}
                    </p>
                    <h4>Shipping Address</h4>
                    <p>
                        {{ $transaksi->nama_customer }}<br>
                        {{ $transaksi->alamat }}<br>
                    </p>
                </div>

                {{-- Kanan: Invoice + Metode Pembayaran --}}
                <div class="transaksi-detail-right">
                    <h4>Invoice Code</h4>
                    <p class="invoice">
                        <button class="invoice-copy-btn" onclick="copyToClipboard('invoiceCode')">
                            <i class="fa-regular fa-clipboard"></i>
                        </button>
                        <span class="invoice-kode" id="invoiceCode">{{ $transaksi->kode_invoice }}</span><br>
                        <small class="invoice-note">* Save the invoice code for further checking</small>
                    </p>

                    <h4>Payment Method</h4>
                    <div class="transaksi-metode">
                        @if($transaksi->pembayaran->metode->nama_pembayaran === 'qris')
                            <img src="{{ route('storage', ['folder' => 'icons', 'filename' => $transaksi->pembayaran->metode->icon]) }}" alt="Metode Logo" class="metode-logo">
                            <img src="{{ route('storage', ['folder' => 'icons', 'filename' => $transaksi->pembayaran->metode->kode_bayar]) }}" 
                                alt="QR Code" class="qrcode">
                        @else
                            <img src="{{ route('storage', ['folder' => 'icons', 'filename' => $transaksi->pembayaran->metode->icon]) }}" alt="Metode Logo" class="metode-logo">
                            <p>
                                <button class="metode-copy-btn" onclick="copyToClipboard('metodeCode')">
                                    <i class="fa-regular fa-clipboard "></i>
                                </button>
                                <span class="metode-kode" id="metodeCode">{{ $transaksi->pembayaran->metode->kode_bayar }}</span>
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="transaksi-status">
                <p class="transaksi-status-pembayaran">payment status:
                    <span class="status-pembayaran">
                        {{ $transaksi->pembayaran->status_pembayaran }}
                    </span>
                </p>
                <p>Transaction Status:
                    <span class="status-transaksi">
                        {{ $transaksi->status_transaksi }}
                    </span>
                </p>
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
                IDR {{ number_format($item->produk->harga * $item->jumlah, 2, ',', '.') }}
            </div>
        </div>
    @endforeach

    {{-- Ringkasan Transaksi --}}
    <div class="transaksi-ringkasan">
        <div class="transaksi-subtotal">
            <span>Subtotal : {{ $transaksi->detail->sum('jumlah') }} item</span>
            <span>Rp {{ number_format($transaksi->pembayaran->total_harga, 0, ',', '.') }}</span>
        </div>
        <div class="transaksi-total">
            <span>Total</span>
            <span>Rp {{ number_format($transaksi->pembayaran->total_harga, 0, ',', '.') }}</span>
        </div>
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
</script>
@endpush
