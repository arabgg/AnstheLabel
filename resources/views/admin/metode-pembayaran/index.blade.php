@extends('admin.layouts.app')

@section('content')
<div class="max-w-5xl mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Daftar Metode Pembayaran</h1>
        <a href="{{ route('metode-pembayaran.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded shadow hover:bg-blue-600">+ Tambah</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <table class="w-full border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-100">
                <th class="border px-3 py-2">ID</th>
                <th class="border px-3 py-2">Nama</th>
                <th class="border px-3 py-2">Kode</th>
                <th class="border px-3 py-2">Status</th>
                <th class="border px-3 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $row)
            <tr>
                <td class="border px-3 py-2">{{ $row->metode_pembayaran_id }}</td>
                <td class="border px-3 py-2 flex items-center gap-2">
                    @if($row->icon)
                        <img src="{{ $row->icon }}" alt="icon" class="w-6 h-6">
                    @endif
                    {{ $row->nama_pembayaran }}
                </td>
                <td class="border px-3 py-2">{{ $row->kode_bayar }}</td>
                <td class="border px-3 py-2">
                    @if($row->status_pembayaran)
                        <span class="px-2 py-1 bg-green-200 text-green-800 rounded">Aktif</span>
                    @else
                        <span class="px-2 py-1 bg-red-200 text-red-800 rounded">Nonaktif</span>
                    @endif
                </td>
                <td class="border px-3 py-2 space-x-2">
                    <a href="{{ route('metode-pembayaran.show',$row->metode_pembayaran_id) }}" class="text-blue-600 hover:underline">Detail</a>
                    <a href="{{ route('metode-pembayaran.edit',$row->metode_pembayaran_id) }}" class="text-yellow-600 hover:underline">Edit</a>
                    <form action="{{ route('metode-pembayaran.destroy',$row->metode_pembayaran_id) }}" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" onclick="return confirm('Yakin hapus?')" class="text-red-600 hover:underline">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center py-3">Belum ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $data->links() }}
    </div>
</div>
@endsection
