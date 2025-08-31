@extends('home.layouts.app')

@section('content')
<div class="container transaksi">
    <h2>Detail Transaksi</h2>
    <!-- Progress Bar -->
    <div class="transaksi-progress {{ $transaksi->status_transaksi === 'batal' ? 'is-batal' : '' }}">
        {{-- Progress Hijau atau Merah --}}
        @if($transaksi->status_transaksi !== 'batal')
            <div class="progress-bar" 
                 style="width: calc({{ $stepIndex }} / ({{ count($steps) - 2 }}) * 75%)"></div>
        @else
            <div class="progress-bar batal" style="width: 75%"></div>
        @endif

        {{-- Render step awal (tanpa selesai & batal) --}}
        @foreach($steps as $key => $step)
            @if(!in_array($key, ['selesai','batal']))
                <div class="transaksi-step {{ $loop->index <= $stepIndex && $transaksi->status_transaksi !== 'batal' ? 'active' : '' }}">
                    <div class="icon"><i class="{{ $step['icon'] }}"></i></div>
                    <p class="step-title">{{ $step['label'] }}</p>
                    <p class="step-desc">{{ $step['desc'] }}</p>
                </div>
            @endif
        @endforeach

        {{-- Step terakhir dinamis: selesai atau batal --}}
        @if($transaksi->status_transaksi === 'batal')
            <div class="transaksi-step batal active">
                <div class="icon"><i class="{{ $steps['batal']['icon'] }}"></i></div>
                <p class="step-title">{{ $steps['batal']['label'] }}</p>
                <p class="step-desc">{{ $steps['batal']['desc'] }}</p>
            </div>
        @else
            <div class="transaksi-step {{ $transaksi->status_transaksi === 'selesai' ? 'active' : '' }}">
                <div class="icon"><i class="{{ $steps['selesai']['icon'] }}"></i></div>
                <p class="step-title">{{ $steps['selesai']['label'] }}</p>
                <p class="step-desc">{{ $steps['selesai']['desc'] }}</p>
            </div>
        @endif
    </div>

    <div class="transaksi-info">
        <p><strong>Kode Invoice:</strong> {{ $transaksi->kode_invoice }}</p>
        <p><strong>Nama Customer:</strong> {{ $transaksi->nama_customer }}</p>
        <p><strong>Alamat:</strong> {{ $transaksi->alamat }}</p>
        <p><strong>Status:</strong> {{ ucfirst($transaksi->status_transaksi) }}</p>
    </div>
</div>
@endsection
