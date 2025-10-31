@extends('admin.layouts.app')

@section('content')
    <div class="container rounded-lg min-h-screen">
        <div class="grid grid-cols-4 gap-5 mb-6">
            <!-- Filter & Mode Tampilan -->
            <div class="bg-white p-6 rounded-lg shadow flex flex-col justify-between">
                <form method="GET" action="{{ route('admin.dashboard') }}" class="space-y-4">
                    {{-- Tahun Dinamis --}}
                    <div>
                        <label for="year" class="block text-sm font-semibold text-gray-700 mb-1">Pilih Tahun</label>
                        <select name="year" id="year"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#560024]">
                            @foreach ($availableYears as $y)
                                <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tombol Mode Tampilan --}}
                    <div class="mt-3">
                        <div class="grid grid-cols-2 gap-3">
                            <button type="submit" name="view_mode" value="monthly"
                                class="px-4 py-2 rounded-lg text-sm font-semibold shadow transition 
                                {{ $viewMode == 'monthly' ? 'bg-[#560024] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                Monthly
                            </button>
                            <button type="submit" name="view_mode" value="quarterly"
                                class="px-4 py-2 rounded-lg text-sm font-semibold shadow transition 
                                {{ $viewMode == 'quarterly' ? 'bg-[#560024] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                Quarterly
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Total Pendapatan -->
            <div class="bg-white p-5 rounded-lg shadow grid grid-cols-3 items-center">
                <div class="col-span-1 flex justify-center">
                    <p class="bg-[#FBE9EB] px-6 py-4 rounded-lg text-[#560024] text-base font-bold">TOTAL PENDAPATAN</p>
                </div>
                <div class="col-span-2 text-center">
                    <h2 class="text-xl font-bold">IDR {{ number_format($pendapatan) }}</h2>
                </div>
            </div>

            <!-- Item Selesai -->
            <div class="bg-white p-5 rounded-lg shadow grid grid-cols-3 items-center">
                <div class="col-span-1 flex justify-center">
                    <p class="bg-[#FBE9EB] px-7 py-4 rounded-lg text-[#560024] text-base font-bold">ITEM SELESAI</p>
                </div>
                <div class="col-span-2 text-center">
                    <h2 class="text-xl font-bold">{{ $orderSelesai }} ITEM</h2>
                </div>
            </div>

            <!-- Total Produk -->
            <div class="bg-white p-5 rounded-lg shadow grid grid-cols-3 items-center">
                <div class="col-span-1 flex justify-center">
                    <p class="bg-[#FBE9EB] px-7 py-4 rounded-lg text-[#560024] text-base font-bold">TOTAL PRODUK</p>
                </div>
                <div class="col-span-2 text-center">
                    <h2 class="text-xl font-bold">{{ $produk }} ITEM</h2>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-6 mb-6">
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="font-bold mb-2">
                    Total Pendapatan ({{ ucfirst($viewMode) }} {{ $selectedYear }})
                </h3>
                <canvas id="revenueChart"></canvas>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="font-bold mb-2">
                    Total Pesanan Selesai ({{ ucfirst($viewMode) }} {{ $selectedYear }})
                </h3>
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
                        <th class="px-4 py-2">Jumlah</th>
                        <th class="px-4 py-2">Tipe Pembayaran</th>
                        <th class="px-4 py-2">Status Pembayaran</th>
                        <th class="px-4 py-2">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr class="text-center border-b text-sm">
                            <td class="px-4 py-4">{{ $order->kode_invoice }}</td>
                            <td class="px-4 py-4">{{ $order->nama_customer }}</td>
                            <td class="px-4 py-4">IDR {{ number_format($order->pembayaran->total_harga) }}</td>
                            <td class="px-4 py-4">{{ $order->pembayaran->metode->nama_pembayaran }}</td>
                            <td class="px-7 py-4">
                                @if ($order->pembayaran->status_pembayaran === 'Menunggu Pembayaran')
                                    <div
                                        class="border-transparent p-2 rounded-lg bg-yellow-200 text-yellow-800 border-yellow-400 font-semibold text-center">
                                        Menunggu Pembayaran
                                    </div>
                                @elseif($order->pembayaran->status_pembayaran === 'Lunas')
                                    <div
                                        class="border-transparent p-2 rounded-lg bg-green-200 text-green-800 border-green-400 font-semibold text-center">
                                        Lunas
                                    </div>
                                @elseif($order->pembayaran->status_pembayaran === 'Dibatalkan')
                                    <div
                                        class="border-transparent p-2 rounded-lg bg-red-200 text-red-800 border-red-400 font-semibold text-center">
                                        Dibatalkan
                                    </div>
                                @else
                                    <div
                                        class="border-transparent p-2 rounded-lg bg-gray-200 text-gray-800 border-gray-400 font-semibold text-center">
                                        {{ ucfirst($order->pembayaran->status_pembayaran) }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-4">{{ $order->created_at->format('d M Y') }}</td>
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
            const baseColor = '#560024';

            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode(array_keys($revenueChart)) !!},
                    datasets: [{
                        label: 'Pendapatan',
                        data: {!! json_encode(array_values($revenueChart)) !!},
                        fill: true,
                        backgroundColor: 'rgba(86, 0, 36, 0.2)',
                        borderColor: baseColor,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

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
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
@endpush
