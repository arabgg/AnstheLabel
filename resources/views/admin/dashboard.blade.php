@extends('admin.layouts.app')

@section('content')
<div class="space-y-4">

    <h1 class="text-2xl font-bold mb-4">{{ $title }}</h1>

    {{-- Form Filter Tanggal Inline (tanpa button) --}}
    <form method="GET" class="flex gap-2 mb-4 items-center" id="filterForm">
        <div>
            <label class="block text-sm font-semibold">Tanggal Mulai</label>
            <input type="date" name="start_date" id="start_date"
                   value="{{ $startDate->format('Y-m-d') }}"
                   class="border rounded p-1"
                   max="{{ now()->format('Y-m-d') }}">
        </div>
        <span class="mx-2 mt-6">-</span>
        <div>
            <label class="block text-sm font-semibold">Tanggal Selesai</label>
            <input type="date" name="end_date" id="end_date"
                   value="{{ $endDate->format('Y-m-d') }}"
                   class="border rounded p-1"
                   max="{{ now()->format('Y-m-d') }}">
        </div>
    </form>

    {{-- Total --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-lg font-bold">Total Pendapatan</h3>
            <p class="text-2xl mt-2 text-green-600">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-lg font-bold">Total Produk Terjual</h3>
            <p class="text-2xl mt-2 text-blue-600">{{ $totalProduk }}</p>
        </div>
    </div>

    {{-- Grafik --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-lg font-bold mb-2">Pendapatan Harian</h3>
            <canvas id="pendapatanChart"></canvas>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-lg font-bold mb-2">Produk Terjual Harian</h3>
            <canvas id="produkChart"></canvas>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const filterForm = document.getElementById('filterForm');

    // Submit otomatis saat tanggal berubah
    startDateInput.addEventListener('change', () => validateAndSubmit());
    endDateInput.addEventListener('change', () => validateAndSubmit());

    function validateAndSubmit() {
        const start = new Date(startDateInput.value);
        const end = new Date(endDateInput.value);
        const diffDays = (end - start) / (1000 * 60 * 60 * 24) + 1;

        if(diffDays > 7) {
            alert('Rentang tanggal maksimal 7 hari!');
            endDateInput.value = startDateInput.value; // reset end date
            return;
        }
        if(diffDays < 1) {
            alert('Tanggal selesai harus sama atau lebih besar dari tanggal mulai!');
            endDateInput.value = startDateInput.value;
            return;
        }
        filterForm.submit();
    }

    // Chart Pendapatan
    const pendapatanLabels = {!! json_encode($pendapatanHarian->pluck('tanggal')) !!};
    const pendapatanData = {!! json_encode($pendapatanHarian->pluck('pendapatan')) !!};

    new Chart(document.getElementById('pendapatanChart'), {
        type: 'line',
        data: {
            labels: pendapatanLabels,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: pendapatanData,
                borderColor: '#16a34a',
                backgroundColor: 'rgba(22, 163, 74, 0.2)',
                tension: 0.3
            }]
        },
        options: { responsive: true, scales: { y: { beginAtZero: true } } }
    });

    // Chart Produk Terjual
    const produkLabels = {!! json_encode($produkHarian->pluck('tanggal')) !!};
    const produkData = {!! json_encode($produkHarian->pluck('produk_terjual')) !!};

    new Chart(document.getElementById('produkChart'), {
        type: 'bar',
        data: {
            labels: produkLabels,
            datasets: [{
                label: 'Produk Terjual',
                data: produkData,
                backgroundColor: '#2563eb'
            }]
        },
        options: { responsive: true, scales: { y: { beginAtZero: true } } }
    });
</script>
@endpush
