@extends('admin.layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Daftar Ukuran</h1>
    <a href="{{ route('ukuran.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Tambah Ukuran</a>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-2 mb-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    <table class="w-full border border-gray-300 rounded">
        <thead>
            <tr class="bg-gray-100">
                <th class="border px-4 py-2">#</th>
                <th class="border px-4 py-2">Nama Ukuran</th>
                <th class="border px-4 py-2">Deskripsi</th>
                <th class="border px-4 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ukuran as $u)
            <tr>
                <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                <td class="border px-4 py-2">{{ $u->nama_ukuran }}</td>
                <td class="border px-4 py-2">{{ $u->deskripsi }}</td>
                <td class="border px-4 py-2 space-x-1">
                    <a href="{{ route('ukuran.show', $u->ukuran_id) }}" class="px-2 py-1 bg-gray-300 rounded hover:bg-gray-400">Detail</a>
                    <a href="{{ route('ukuran.edit', $u->ukuran_id) }}" class="bg-yellow-500 text-white px-2 py-1 rounded">Edit</a>
                    <form action="{{ route('ukuran.destroy', $u->ukuran_id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin hapus?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
