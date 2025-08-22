@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">Daftar Bahan</h1>
    <a href="{{ route('bahan.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Tambah Bahan</a>

    <table class="table-auto w-full mt-4 border">
        <thead class="bg-gray-200">
            <tr>
                <th class="px-4 py-2 border">ID</th>
                <th class="px-4 py-2 border">Nama Bahan</th>
                <th class="px-4 py-2 border">Deskripsi</th>
                <th class="px-4 py-2 border">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bahan as $row)
            <tr>
                <td class="border px-4 py-2">{{ $row->bahan_id }}</td>
                <td class="border px-4 py-2">{{ $row->nama_bahan }}</td>
                <td class="border px-4 py-2">{{ $row->deskripsi }}</td>
                <td class="border px-4 py-2 flex gap-2">
                    <a href="{{ route('bahan.show', $row->bahan_id) }}" class="text-blue-500">Detail</a>
                    <a href="{{ route('bahan.edit', $row->bahan_id) }}" class="text-green-500">Edit</a>
                    <form action="{{ route('bahan.destroy', $row->bahan_id) }}" method="POST" onsubmit="return confirm('Yakin hapus?')">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-500">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center py-4">Belum ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
