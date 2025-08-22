@extends('home.layouts.app')

@section('content')
<div class="container">
    <h2>Detail Transaksi</h2>
    <p><strong>Kode Invoice:</strong> {{ $transaksi->kode_invoice }}</p>
    <p><strong>Nama Customer:</strong> {{ $transaksi->nama_customer }}</p>
    <p><strong>Alamat:</strong> {{ $transaksi->alamat }}</p>
    <p><strong>Status:</strong> {{ $transaksi->status_transaksi }}</p>
</div>
@endsection
