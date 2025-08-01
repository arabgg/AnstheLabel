@extends('admin.layouts.index')

@section('content')
    <h1>Edit Produk</h1>

    <form action="/produk/{{ $produk->produk_id }}/update" method="POST">
        @csrf
        @method('PUT')

        <label>Nama Produk</label>
        <input type="text" name="nama_produk" value="{{ $produk->nama_produk }}" required>

        <label>Deskripsi</label>
        <textarea name="deskripsi" required>{{ $produk->deskripsi }}</textarea>

        <label>Kategori</label>
        <select name="kategori_id">
            @foreach ($kategori as $k)
                <option value="{{ $k->kategori_id }}" {{ $produk->kategori_id == $k->kategori_id ? 'selected' : '' }}>
                    {{ $k->nama_kategori }}</option>
            @endforeach
        </select>

        <label>Bahan</label>
        <select name="bahan_id">
            @foreach ($bahan as $b)
                <option value="{{ $b->bahan_id }}" {{ $produk->bahan_id == $b->bahan_id ? 'selected' : '' }}>
                    {{ $b->nama_bahan }}</option>
            @endforeach
        </select>

        <button type="submit">Perbarui</button>
    </form>
@endsection
