@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="grid grid-cols-4  gap-5 mb-6">
        <div class="bg-white p-6 rounded-lg shadow flex items-center justify-between">
            <i class="bg-[#FBE9EB] px-6 py-5 rounded-lg fa-solid fa-dollar-sign text-[#560024] text-xl"></i>
            <div class="text-left">
                <p class="text-gray-500">Total Pendapatan</p>
                <h2 class="text-xl font-bold">IDR {{ number_format($pendapatan) }}</h2>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow flex items-center justify-between">
            <i class="bg-[#FBE9EB] p-5 rounded-lg fa-solid fa-box-open text-[#560024] text-xl ml-3"></i>
            <div class="text-left mr-3">
                <p class="text-gray-500">Item Selesai</p>
                <h2 class="text-xl font-bold">{{ $orderSelesai }}</h2>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow flex items-center justify-between">
            <i class="bg-[#FBE9EB] px-6 py-5 rounded-lg fa-solid fa-box text-[#560024] text-xl ml-2"></i>
            <div class="text-left mr-2">
                <p class="text-gray-500">Item Diproses</p>
                <h2 class="text-2xl font-bold">{{ $orderProses }}</h2>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow flex items-center justify-between">
            <i class="bg-[#FBE9EB] px-6 py-5 rounded-lg fa-solid fa-bag-shopping text-[#560024] text-xl ml-3"></i>
            <div class="text-left mr-3">
                <p class="text-gray-500">Total Produk</p>
                <h2 class="text-2xl font-bold">{{ $produk }}</h2>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-2 gap-6 mb-6">
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="font-bold mb-2">Total Pendapatan</h3>
            <canvas id="revenueChart"></canvas>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="font-bold mb-2">Total Pesanan</h3>
            <canvas id="ordersChart"></canvas>
        </div>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="font-bold mb-4">Pesanan Baru</h3>
        <table class="min-w-full table-auto">
            <thead>
                <tr class="bg-[#560024] text-white text-base">
                    <th class="px-4 py-2">Faktur</th>
                    <th class="px-4 py-2">Pelanggan</th>
                    <th class="px-4 py-2">Tanggal</th>
                    <th class="px-4 py-2">Jumlah</th>
                    <th class="px-4 py-2">Tipe Pembayaran</th>
                    <th class="px-4 py-2">Status Pembayaran</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr class="text-center border-b text-sm">
                    <td class="px-4 py-4">{{ $order->kode_invoice }}</td>
                    <td class="px-4 py-4">{{ $order->nama_customer }}</td>
                    <td class="px-4 py-4">{{ $order->created_at->format('d M Y') }}</td>
                    <td class="px-4 py-4">IDR {{ number_format($order->pembayaran->total_harga) }}</td>
                    <td class="px-4 py-4">{{ $order->pembayaran->metode->nama_pembayaran }}</td>
                    <td class="px-7 py-4">
                        @if($order->pembayaran->status_pembayaran === 'pending')
                            <div class="border-transparent p-2 rounded-lg bg-yellow-200 text-yellow-800 border-yellow-400 font-semibold text-center">
                                Pending
                            </div>
                        @elseif($order->pembayaran->status_pembayaran === 'lunas')
                            <div class="border-transparent p-2 rounded-lg bg-green-200 text-green-800 border-green-400 font-semibold text-center">
                                Lunas
                            </div>
                        @elseif($order->pembayaran->status_pembayaran === 'gagal')
                            <div class="border-transparent p-2 rounded-lg bg-red-200 text-red-800 border-red-400 font-semibold text-center">
                                Gagal
                            </div>
                        @else
                            <div class="border-transparent p-2 rounded-lg bg-gray-200 text-gray-800 border-gray-400 font-semibold text-center">
                                {{ ucfirst($order->pembayaran->status_pembayaran) }}
                            </div>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Warna dasar dari #560024
    const baseColor = '#560024';
    
    // Revenue Chart (Diagram Pendapatan)
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_keys($revenueChart)) !!},
            datasets: [{
                label: 'Pendapatan',
                data: {!! json_encode(array_values($revenueChart)) !!},
                fill: true,
                backgroundColor: 'rgba(86, 0, 36, 0.2)', // Warna latar belakang yang lebih terang
                borderColor: baseColor,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Orders Chart (Diagram Pesanan)
    const ordersCtx = document.getElementById('ordersChart').getContext('2d');
    new Chart(ordersCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_keys($ordersChart)) !!},
            datasets: [{
                label: 'Pesanan',
                data: {!! json_encode(array_values($ordersChart)) !!},
                backgroundColor: baseColor,
                borderRadius: 50,
                barPercentage: 0.5
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
});
</script>
@endpush            