@extends('admin.layouts.app')

@section('content')
    <h1>Konfirmasi Hapus Produk</h1>
    <p>Yakin ingin menghapus produk <strong>{{ $produk->nama_produk }}</strong>?</p>

    <form action="/produk/{{ $produk->produk_id }}/delete" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit">Ya, Hapus</button>
        <a href="/produk">Batal</a>
    </form>
@endsection
