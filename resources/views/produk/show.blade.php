@extends('admin.layouts.app')

@section('content')
    <h1>Detail Produk</h1>

    <p><strong>Nama:</strong> {{ $produk->nama_produk }}</p>
    <p><strong>Deskripsi:</strong> {{ $produk->deskripsi }}</p>
    <p><strong>Kategori:</strong> {{ $produk->kategori->nama_kategori ?? '-' }}</p>
    <p><strong>Bahan:</strong> {{ $produk->bahan->nama_bahan ?? '-' }}</p>

    <a href="/produk">Kembali</a>
@endsection
