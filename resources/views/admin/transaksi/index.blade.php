@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Daftar Transaksi</h1>

    <div class="flex justify-end mb-4">
        <a href="{{ route('transaksi.export') }}" class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium rounded-lg shadow">
            <i class="bi bi-file-earmark-excel mr-2"></i> Export Excel
        </a>
    </div>

    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Kode Invoice</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Customer</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Tanggal</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($transaksis as $t)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-700 font-medium">{{ $t->kode_invoice }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $t->nama_customer }}</td>
                    <td class="px-6 py-4">
                        @if($t->status_transaksi == 'pending')
                            <span class="px-3 py-1 rounded-full text-sm bg-yellow-200 text-yellow-800 font-semibold">Pending</span>
                        @elseif($t->status_transaksi == 'selesai')
                            <span class="px-3 py-1 rounded-full text-sm bg-green-200 text-green-800 font-semibold">Selesai</span>
                        @elseif($t->status_transaksi == 'dibatalkan')
                            <span class="px-3 py-1 rounded-full text-sm bg-red-200 text-red-800 font-semibold">Dibatalkan</span>
                        @else
                            <span class="px-3 py-1 rounded-full text-sm bg-blue-200 text-blue-800 font-semibold">{{ ucfirst($t->status_transaksi) }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $t->created_at->format('d-m-Y H:i') }}</td>
                    <td class="px-6 py-4 flex space-x-2">
                        <a href="{{ route('transaksi.show', $t->transaksi_id) }}" class="inline-flex items-center px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded shadow">
                            <i class="bi bi-eye mr-1"></i> Detail
                        </a>
                        <a href="{{ route('transaksi.edit', $t->transaksi_id) }}" class="inline-flex items-center px-3 py-1 bg-yellow-400 hover:bg-yellow-500 text-white text-sm rounded shadow">
                            <i class="bi bi-pencil-square mr-1"></i> Edit
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
