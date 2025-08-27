@extends('home.layouts.app')

@section('content')
<div class="transaksi-container">
    <div class="transaksi-header">
        @if ($transaksi->status_transaksi == 'menunggu pembayaran')
            <h2 class="transaksi-title">{{ $transaksi->status_transaksi }}</h2>
            <p class="transaksi-subtitle">Silahkan melakukan pembayaran dengan metode yang kamu pilih</p>
        @endif
    </div>

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
    <div class="transaksi-timer">
        <span>2 Hours 59 Minute 59 Second</span>
    </div>

    <div class="transaksi-content">
        <!-- Box Detail Pesanan -->
        <div class="transaksi-left">
<div class="transaksi-card">
    {{-- Kiri: Detail Pesanan --}}
    <div class="transaksi-left">
        <h4>Detail Pesanan</h4>
        <p><strong>Informasi Kontak</strong><br>
            {{ $transaksi->email }}
        </p>
        <p><strong>Alamat Pengiriman</strong><br>
            {{ $transaksi->nama_customer }}<br>
            {{ $transaksi->alamat }}<br>
            {{ $transaksi->no_hp }}
        </p>
    </div>

    {{-- Kanan: Invoice + Metode Pembayaran --}}
    <div class="transaksi-right">
        <p class="invoice">
            <strong>No Invoice :</strong> {{ $transaksi->kode_invoice }}
            <span class="copy-icon">📋</span>
        </p>

        <h4>Metode Pembayaran</h4>
        <div class="transaksi-qris">
            <img src="{{ asset('images/qris.png') }}" alt="QRIS Logo" class="qris-logo">
            @if($transaksi->pembayaran?->qrcode)
                <img src="{{ asset('storage/'.$transaksi->pembayaran->qrcode) }}" 
                     alt="QR Code" class="qrcode">
            @endif
        </div>
        <p class="qris-caption">*Klik Untuk Memperbesar</p>
    </div>
</div>


            <div class="transaksi-status">
                <p>Status Pembayaran: 
                    <span class="{{ $transaksi->status_pembayaran === 'paid' ? 'transaksi-paid' : 'transaksi-pending' }}">
                        {{ ucfirst($transaksi->status_pembayaran) }}
                    </span>
                </p>
                <p>Status Transaksi: 
                    <span class="{{ $transaksi->status_transaksi === 'pending' ? 'transaksi-pending' : 'transaksi-paid' }}">
                        {{ ucfirst($transaksi->status_transaksi) }}
                    </span>
                </p>
            </div>
        </div>

        <!-- Box Ringkasan Order -->
        <div class="transaksi-right">
            @foreach($transaksi->detail as $item)
    <div class="transaksi-item">
        <img src="{{ storage_url('foto_produk', $item->produk->fotoUtama->foto_produk) }}" alt="{{ $item->produk->nama_produk }}">
        <div class="transaksi-item-info">
            <p class="transaksi-item-nama">{{ $item->produk->nama_produk }}</p>
            <p class="transaksi-item-harga">
                IDR {{ number_format($item->produk->harga,0,',','.') }}
            </p>
            <p>
                Warna: {{ $item->warna->nama_warna ?? '-' }}<br>
                Ukuran: {{ $item->ukuran->nama_ukuran ?? '-' }}
            </p>
        </div>
    </div>
@endforeach


            <div class="transaksi-ringkasan">
                <p><strong>Subtotal:</strong> {{ count($transaksi->detail) }} item</p>
                <p><strong>Total:</strong> Rp{{ number_format($transaksi->pembayaran->total_harga,0,',','.') }}</p>
                <small>*Harga belum termasuk ongkir</small>
            </div>
        </div>
    </div>
</div>
@endsection
