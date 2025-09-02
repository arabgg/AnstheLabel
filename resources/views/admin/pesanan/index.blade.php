@extends('admin.layouts.app')

@section('content')
<div class="p-8 bg-white rounded-lg shadow">
    {{-- Judul --}}
    <div class="flex justify-between items-start mb-7 border-b border-gray-300 pb-4">
        <h1 class="text-2xl font-bold pl-4 pt-4">Manage Pesanan Customer</h1>
    </div>

    {{-- Search --}}
    <div class="flex justify-end mb-5">
        <form method="GET" action="{{ route('pesanan.index') }}" class="flex items-center border rounded px-3 py-2 w-1/3">
            <input type="text" name="search" placeholder="Search Pesanan Customer" value="{{ $searchQuery ?? '' }}" class="w-full outline-none placeholder:text-sm">
            <button type="submit" class="ml-2">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>

    {{-- Tabel item --}}
    <div class="overflow-x-auto rounded-lg">
    <table class="w-full table-fixed border-collapse text-center">
        <thead class="bg-[#560024] text-white text-sm">
            <tr>
                <th class="p-3 w-[12%]">INVOICE</th>
                <th class="p-3 w-[10%]">CUSTOMER</th>
                <th class="p-3 w-[25%]">ALAMAT</th>
                <th class="p-3 w-[12%]">KONTAK</th>
                <th class="p-3 w-[15%]">PEMBAYARAN</th>
                <th class="p-3 w-[13%]">STATUS PEMBAYARAN</th>
                <th class="p-3 w-[13%]">STATUS TRANSAKSI</th>
                <th class="p-3 w-[10%]">ACTION</th>
            </tr>
        </thead>
        <tbody class="text-sm">
            @forelse ($pesanan as $item)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-3">{{ $item->kode_invoice }}</td>
                    <td class="p-3">{{ $item->nama_customer }}</td>
                    <td class="p-3 break-words">{{ $item->alamat }}</td>
                    <td class="p-3">{{ $item->no_telp }}</td>
                    <td class="p-3">{{ $item->pembayaran->metode->nama_pembayaran }}</td>
                    <td>
                        <select onchange="updateStatusPembayaran('{{ route('update.pembayaran', $item->pembayaran->pembayaran_id) }}', this.value)">
                            <option value="pending" {{ $item->pembayaran->status_pembayaran === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="lunas" {{ $item->pembayaran->status_pembayaran === 'lunas' ? 'selected' : '' }}>Lunas</option>
                            <option value="gagal" {{ $item->pembayaran->status_pembayaran === 'gagal' ? 'selected' : '' }}>Gagal</option>
                        </select>
                    </td>
                    <td>
                        <select onchange="updateStatusTransaksi('{{ route('update.transaksi', $item->transaksi_id) }}', this.value)">
                            <option value="menunggu pembayaran" {{ $item->status_transaksi === 'menunggu pembayaran' ? 'selected' : '' }}>Menunggu</option>
                            <option value="dikemas" {{ $item->status_transaksi === 'dikemas' ? 'selected' : '' }}>Dikemas</option>
                            <option value="dikirim" {{ $item->status_transaksi === 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                            <option value="selesai" {{ $item->status_transaksi === 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="batal" {{ $item->status_transaksi === 'batal' ? 'selected' : '' }}>Batal</option>
                        </select>
                    </td>
                    {{-- Action --}}
                    <td class="p-3 mt-4 flex gap-2 justify-center items-center">
                        <button 
                            class="flex items-center justify-center py-2 px-3 rounded-lg border border-gray-400 text-black hover:bg-blue-400 hover:border-blue-400"
                            onclick="openitemModal('{{ route('pesanan.show', ['id' => $item->transaksi_id]) }}')">
                            <i class="fa-solid fa-database"></i>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="p-3 text-center text-gray-500">Item tidak ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

    {{-- Pagination --}}
    <div class="mt-4 flex justify-center space-x-1">
        @if ($pesanan->onFirstPage() === false)
            <a href="{{ $pesanan->previousPageUrl() }}" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">&laquo;</a>
        @endif

        @foreach ($pesanan->getUrlRange(1, $pesanan->lastPage()) as $page => $url)
            <a href="{{ $url }}" class="px-3 py-1 rounded 
                {{ $pesanan->currentPage() === $page ? 'bg-[#560024] text-white' : 'bg-gray-200 hover:bg-gray-300' }}">
                {{ $page }}
            </a>
        @endforeach

        @if ($pesanan->hasMorePages())
            <a href="{{ $pesanan->nextPageUrl() }}" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">&raquo;</a>
        @endif
    </div>
</div>

{{-- Modal --}}
<div id="itemModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div id="modalContent" class="mx-2 w-full max-w-lg">
        {{-- Konten show.blade.php akan dimuat di sini --}}
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function updateStatusPembayaran(url, status) {
    fetch(url, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ status_pembayaran: status })
    })
    .then(res => res.json())
    .then(data => Swal.fire({
        icon: 'success',
        title: data.message,
        timer: 1500,
        showConfirmButton: false,
        position: 'top-end', // ⬅ posisi kanan atas
        toast: true           // ⬅ membuat tampilan seperti toast
    }))
    .catch(err => console.error(err));
}

function updateStatusTransaksi(url, status) {
    fetch(url, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ status_transaksi: status })
    })
    .then(res => res.json())
    .then(data => Swal.fire({
        icon: 'success',
        title: data.message,
        timer: 1500,
        showConfirmButton: false,
        position: 'top-end', // kanan atas
        toast: true
    }))
    .catch(err => console.error(err));
}
</script>
<script>
    function openitemModal(url) {
        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.text())
        .then(html => {
            document.getElementById('modalContent').innerHTML = html;
            document.getElementById('itemModal').classList.remove('hidden');
        })
        .catch(err => console.error(err));
    }

    function closeModal() {
        document.getElementById('itemModal').classList.add('hidden');
        document.getElementById('modalContent').innerHTML = '';
    }
</script>
@endpush
