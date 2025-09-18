@extends('admin.layouts.app')

@section('content')
    <div class="container bg-white rounded-lg min-h-screen">
        <div class="p-8 bg-white rounded-lg shadow">
            {{-- Judul --}}
            <div class="flex justify-between items-start mb-7 border-b border-gray-300 pb-4">
                <h1 class="text-2xl font-bold pl-4 pt-4">Kelola Pesanan Pelanggan</h1>
            </div>

            {{-- Search & Filter --}}
            <div class="flex justify-between mb-5">
                {{-- Search di kiri --}}
                 <form method="GET" action="{{ route('pesanan.index') }}"
                    class="flex items-center border rounded-lg px-3 py-2 w-1/2">
                    <input type="text" name="search" placeholder="Cari Pesanan Pelanggan" value="{{ request('search') }}"
                        class="w-full outline-none placeholder:text-sm">
                    <button type="submit" class="ml-2">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
                {{-- Dropdown Status Transaksi --}}
                <select name="status" class="border rounded-lg px-3 py-2 text-sm">
                    <option value="">Semua Status</option>
                    <option value="menunggu pembayaran" {{ request('status') == 'menunggu pembayaran' ? 'selected' : '' }}>
                        Menunggu Pembayaran
                    </option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>
                        Selesai
                    </option>
                    <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>
                        Dibatalkan
                    </option>
                </select>

                {{-- Filter di kanan --}}
                <form method="GET" action="{{ route('pesanan.index') }}" class="flex items-center gap-2">

                    {{-- Filter tanggal --}}
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                        class="border rounded-lg px-3 py-2 text-sm">
                    <span>-</span>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                        class="border rounded-lg px-3 py-2 text-sm">

                    <button type="submit" class="px-4 py-2 bg-[#560024] text-white rounded-lg hover:bg-gray-700 text-sm">
                        Filter
                    </button>

                    <a href="{{ route('transaksi.export.excel', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Export Excel
                    </a>
                </form>
            </div>


            {{-- Tabel item --}}
            <div class="overflow-x-auto rounded-lg">
                <table class="w-full table-fixed border-collapse text-center">
                    <thead class="bg-[#560024] text-white text-sm">
                        <tr>
                            <th class="p-3">KODE INVOICE</th>
                            <th class="p-3">PELANGGAN</th>
                            <th class="p-3">KONTAK</th>
                            <th class="p-3">STATUS PEMBAYARAN</th>
                            <th class="p-3">STATUS TRANSAKSI</th>
                            <th class="p-3">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse ($pesanan as $item)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3">{{ $item->kode_invoice }}</td>
                                <td class="p-3">{{ $item->nama_customer }}</td>
                                <td class="p-3">{{ $item->no_telp }}</td>
                                <td class="p-3">
                                    @if ($item->pembayaran->status_pembayaran === 'menunggu pembayaran')
                                        <div
                                            class="border-transparent p-2 rounded-lg bg-yellow-200 text-yellow-800 border-yellow-400 font-semibold text-center">
                                            Menunggu Pembayaran
                                        </div>
                                    @elseif($item->pembayaran->status_pembayaran === 'lunas')
                                        <div
                                            class="border-transparent p-2 rounded-lg bg-green-200 text-green-800 border-green-400 font-semibold text-center">
                                            Lunas
                                        </div>
                                    @elseif($item->pembayaran->status_pembayaran === 'dibatalkan')
                                        <div
                                            class="border-transparent p-2 rounded-lg bg-red-200 text-red-800 border-red-400 font-semibold text-center">
                                            Dibatalkan
                                        </div>
                                    @else
                                        <div
                                            class="border-transparent p-2 rounded-lg bg-gray-200 text-gray-800 border-gray-400 font-semibold text-center">
                                            {{ ucfirst($item->pembayaran->status_pembayaran) }}
                                        </div>
                                    @endif
                                </td>

                                {{-- Dropdown Status Transaksi --}}
                                <td>
                                    <select
                                        onchange="updateStatusTransaksi('{{ route('update.transaksi', $item->transaksi_id) }}', this.value)">
                                        <option value="menunggu pembayaran"
                                            {{ $item->status_transaksi === 'menunggu pembayaran' ? 'selected' : '' }}>
                                            Menunggu
                                        </option>
                                        <option value="dikemas"
                                            {{ $item->status_transaksi === 'dikemas' ? 'selected' : '' }}>
                                            Dikemas</option>
                                        <option value="dikirim"
                                            {{ $item->status_transaksi === 'dikirim' ? 'selected' : '' }}>
                                            Dikirim</option>
                                        <option value="selesai"
                                            {{ $item->status_transaksi === 'selesai' ? 'selected' : '' }}>
                                            Selesai</option>
                                        <option value="batal" {{ $item->status_transaksi === 'batal' ? 'selected' : '' }}>
                                            Batal</option>
                                    </select>
                                </td>
                                {{-- Action --}}
                                <td class="p-3 mt-4 flex gap-2 justify-center items-center">
                                    <a href="javascript:void(0);"
                                        onclick="openModal('{{ route('pesanan.show', ['id' => $item->transaksi_id]) }}')"
                                        class="flex items-center justify-center py-2 px-3 rounded-lg border border-gray-400 text-black hover:bg-blue-400 hover:border-blue-400">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr class="border-b hover:bg-gray-50">
                                <td colspan="6" class="p-3 text-center text-gray-500">Item tidak ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-4 flex justify-center space-x-1">
                @if ($pesanan->onFirstPage() === false)
                    <a href="{{ $pesanan->previousPageUrl() }}"
                        class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">&laquo;</a>
                @endif

                @foreach ($pesanan->getUrlRange(1, $pesanan->lastPage()) as $page => $url)
                    <a href="{{ $url }}"
                        class="px-3 py-1 rounded 
                {{ $pesanan->currentPage() === $page ? 'bg-[#560024] text-white' : 'bg-gray-200 hover:bg-gray-300' }}">
                        {{ $page }}
                    </a>
                @endforeach

                @if ($pesanan->hasMorePages())
                    <a href="{{ $pesanan->nextPageUrl() }}"
                        class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">&raquo;</a>
                @endif
            </div>
        </div>

        {{-- Modal --}}
        <div id="itemModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div id="modalContent" class="mx-2 w-full max-w-lg">
                {{-- Konten show.blade.php akan dimuat di sini --}}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function openModal(url) {
            fetch(url)
                .then(res => res.text())
                .then(html => {
                    document.getElementById('modalContent').innerHTML = html;
                    document.getElementById('itemModal').classList.remove('hidden');
                });
        }

        function closeModal() {
            document.getElementById('itemModal').classList.add('hidden');
        }
    </script>
    <script>
        function updateStatusTransaksi(url, status) {
            fetch(url, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        status_transaksi: status
                    })
                })
                .then(res => res.json())
                .then(data => {
                    Swal.fire({
                        icon: 'success',
                        title: data.message,
                        timer: 1000,
                        showConfirmButton: false,
                        position: 'top-end',
                        toast: true
                    }).then(() => {
                        // reload halaman setelah alert selesai
                        location.reload();
                    });
                })
                .catch(err => console.error(err));
        }
    </script>
@endpush
