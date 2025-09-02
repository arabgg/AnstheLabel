@extends('admin.layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Detail Metode Pembayaran</h1>

    <div class="bg-white shadow rounded p-4 space-y-4">
        <div>
            <strong>ID:</strong> {{ $metode->metode_pembayaran_id }}
        </div>
        <div>
            <strong>Nama:</strong> {{ $metode->nama_pembayaran }}
        </div>
        <div>
            <strong>Kode Bayar:</strong> {{ $metode->kode_bayar }}
        </div>
        <div>
            <strong>Status:</strong> 
            @if($metode->status_pembayaran)
                <span class="px-2 py-1 bg-green-200 text-green-800 rounded">Aktif</span>
            @else
                <span class="px-2 py-1 bg-red-200 text-red-800 rounded">Nonaktif</span>
            @endif
        </div>
        <div>
            <strong>Icon:</strong> 
            @if($metode->icon)
                <img src="{{ asset('storage/icons/' . $metode->icon) }}" alt="icon" class="w-12 h-12 mt-1">
            @else
                <span class="text-gray-500">Tidak ada</span>
            @endif
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('metode_pembayaran.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Kembali</a>
    </div>
</div>
@endsection
