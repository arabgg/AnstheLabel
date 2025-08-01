@extends('admin.layouts.index')

@section('content')
    <h1>Tambah Produk</h1>

    <form action="/produk/upload" method="POST">
        @csrf
        <label>Nama Produk</label>
        <input type="text" name="nama_produk" required>

        <label>Deskripsi</label>
        <textarea name="deskripsi" required></textarea>

        <label>Kategori</label>
        <select name="kategori_id">
            @foreach ($kategori as $k)
                <option value="{{ $k->kategori_id }}">{{ $k->nama_kategori }}</option>
            @endforeach
        </select>

        <label>Bahan</label>
        <select name="bahan_id">
            @foreach ($bahan as $b)
                <option value="{{ $b->bahan_id }}">{{ $b->nama_bahan }}</option>
            @endforeach
        </select>

        <button type="submit">Simpan</button>
    </form>
@endsection
