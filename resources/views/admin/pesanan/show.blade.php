@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detail Transaksi {{ $transaksi->kode_invoice }}</h1>
    <p><strong>Customer:</strong> {{ $transaksi->nama_customer }}</p>
    <p><strong>No Telp:</strong> {{ $transaksi->no_telp }}</p>
    <p><strong>Email:</strong> {{ $transaksi->email }}</p>
    <p><strong>Alamat:</strong> {{ $transaksi->alamat }}</p>
    <p><strong>Status:</strong> {{ ucfirst($transaksi->status_transaksi) }}</p>

    <h3>Detail Produk</h3>
    <ul>
        @foreach($transaksi->detail as $d)
            <li>{{ $d->produk->nama_produk }} - {{ $d->qty }} x {{ $d->harga }}</li>
        @endforeach
    </ul>

    <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection
