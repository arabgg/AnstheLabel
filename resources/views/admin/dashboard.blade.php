@extends('admin.layouts.app')

@section('content')
<!-- Top Summary Cards -->
<div class="grid grid-cols-4 gap-4 mb-6">
    <div class="bg-white p-6 rounded-lg shadow text-center">
        <p class="text-gray-500">Total Pendapatan</p>
        <h2 class="text-2xl font-semibold">IDR {{ number_format($pendapatan) }}</h2>
    </div>
    <div class="bg-white p-6 rounded-lg shadow text-center">
        <p class="text-gray-500">Pesanan Selesai</p>
        <h2 class="text-2xl font-semibold">{{ $orderSelesai }}</h2>
    </div>
    <div class="bg-white p-6 rounded-lg shadow text-center">
        <p class="text-gray-500">Pesanan Diproses</p>
        <h2 class="text-2xl font-semibold">{{ $orderProses }}</h2>
    </div>
    <div class="bg-white p-6 rounded-lg shadow text-center">
        <p class="text-gray-500">Total Produk</p>
        <h2 class="text-2xl font-semibold">{{ $produk }}</h2>
    </div>
</div>

<!-- Charts -->
<div class="grid grid-cols-2 gap-6 mb-6">
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="font-bold mb-2">Total Revenue</h3>
        <canvas id="revenueChart"></canvas>
    </div>
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="font-bold mb-2">Total Orders</h3>
        <canvas id="ordersChart"></canvas>
    </div>
</div>

<!-- New Orders Table -->
<div class="bg-white p-6 rounded-lg shadow">
    <h3 class="font-bold mb-4">New Orders</h3>
    <table class="min-w-full table-auto">
        <thead>
            <tr class="bg-gray-100">
                <th class="px-4 py-2">Invoice</th>
                <th class="px-4 py-2">Customer</th>
                <th class="px-4 py-2">Date</th>
                <th class="px-4 py-2">Amount</th>
                <th class="px-4 py-2">Payment Type</th>
                <th class="px-4 py-2">Payment Status</th>
                <th class="px-4 py-2">Transaction</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr class="text-center border-b">
                <td class="px-4 py-2">{{ $order->kode_invoice }}</td>
                <td class="px-4 py-2">{{ $order->nama_customer }}</td>
                <td class="px-4 py-2">{{ $order->created_at->format('d M Y') }}</td>
                <td class="px-4 py-2">IDR {{ number_format($order->pembayaran->total_harga) }}</td>
                <td class="px-4 py-2">{{ $order->pembayaran->metode->nama_pembayaran }}</td>
                <td class="px-4 py-2">{{ $order->pembayaran->status_pembayaran }}</td>
                <td class="px-4 py-2">{{ $order->status_transaksi }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_keys($revenueChart)) !!},
            datasets: [{
                label: 'Revenue',
                data: {!! json_encode(array_values($revenueChart)) !!},
                fill: true,
                backgroundColor: 'rgba(99, 102, 241, 0.2)',
                borderColor: 'rgba(99, 102, 241, 1)',
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

    // Orders Chart
    const ordersCtx = document.getElementById('ordersChart').getContext('2d');
    new Chart(ordersCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_keys($ordersChart)) !!},
            datasets: [{
                label: 'Orders',
                data: {!! json_encode(array_values($ordersChart)) !!},
                backgroundColor: 'rgba(99, 102, 241, 1)'
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
