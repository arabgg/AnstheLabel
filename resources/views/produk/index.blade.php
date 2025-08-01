@extends('admin.layouts.app')
@section('content')
<div class="p-4">
    <h1 class="text-xl font-bold mb-4">Daftar Produk</h1>
    <a href="{{ url('/produk/create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Tambah Produk</a>
    <table class="table-auto w-full mt-4">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-2">Nama</th>
                <th class="p-2">Kategori</th>
                <th class="p-2">Bahan</th>
                <th class="p-2">Foto</th>
                <th class="p-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($produk as $p)
                <tr class="border-t">
                    <td class="p-2">{{ $p->nama_produk }}</td>
                    <td class="p-2">{{ $p->kategori->nama_kategori ?? '-' }}</td>
                    <td class="p-2">{{ $p->bahan->nama_bahan ?? '-' }}</td>
                    <td class="p-2">
                        @if ($p->fotoUtama)
                            <img src="{{ asset('storage/foto_produk/' . $p->fotoUtama->foto_produk) }}" class="w-16 h-16 object-cover">
                        @endif
                    </td>
                    <td class="p-2 space-x-2">
                        <a href="{{ url('/produk/show/' . $p->produk_id) }}" class="text-blue-600">Lihat</a>
                        <a href="{{ url('/produk/edit/' . $p->produk_id . '/edit') }}" class="text-yellow-600">Edit</a>
                        <a href="{{ url('/produk/delete/' . $p->produk_id . '/confirm') }}" class="text-red-600">Hapus</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
