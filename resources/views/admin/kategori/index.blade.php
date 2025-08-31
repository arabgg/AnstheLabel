@extends('admin.layouts.app')

@section('content')
<div class="p-6 bg-white rounded shadow-md max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Daftar Kategori</h1>
        <a href="{{ route('kategori.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Tambah Kategori
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <table class="w-full border border-gray-200 rounded">
        <thead>
            <tr class="bg-gray-100 text-left">
                <th class="p-2 border-b">#</th>
                <th class="p-2 border-b">Nama Kategori</th>
                <th class="p-2 border-b">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kategori as $k)
            <tr class="hover:bg-gray-50">
                <td class="p-2 border-b">{{ $loop->iteration }}</td>
                <td class="p-2 border-b">{{ $k->nama_kategori }}</td>
                <td class="p-2 border-b flex gap-2">
                    <a href="{{ route('kategori.show', $k->kategori_id) }}" class="px-2 py-1 bg-gray-300 rounded hover:bg-gray-400">Detail</a>
                    <a href="{{ route('kategori.edit', $k->kategori_id) }}" class="px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">Edit</a>
                    <form action="{{ route('kategori.destroy', $k->kategori_id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
