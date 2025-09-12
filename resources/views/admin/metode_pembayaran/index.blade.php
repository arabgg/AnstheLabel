@extends('admin.layouts.app')

@section('content')
    <div class="container bg-white rounded-lg min-h-screen">
        <div class="p-8 bg-white rounded-lg shadow">
            {{-- Judul --}}
            <div class="flex justify-between items-start mb-7 border-b border-gray-300 pb-4">
                <h1 class="text-2xl font-bold pl-4 pt-4">Manage Metode Pembayaran</h1>
            </div>

            {{-- Search & Filter Form --}}
            <div class="flex justify-between items-center mb-7 mt-6">
                {{-- Search & Filter Form --}}
                <form id="filterForm" method="GET" action="{{ route('metode_pembayaran.index') }}"
                    class="flex items-center w-full">

                    {{-- Input Search --}}
                    <div class="flex-grow mr-3 flex items-center border rounded-lg px-3 py-2">
                        <input type="text" name="search" placeholder="Cari Metode Pembayaran"
                            value="{{ request('search') }}" class="w-full outline-none placeholder:text-sm">
                        <button type="submit" class="ml-2">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>

                    {{-- Dropdown Metode --}}
                    <div class="mr-3">
                        <select id="metodeFilter" name="metode_id"
                            class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-gray-400 transition-colors">
                            <option value="">Semua Metode</option>
                            <option value="1" {{ request('metode_id') == 1 ? 'selected' : '' }}>Transfer Bank</option>
                            <option value="2" {{ request('metode_id') == 2 ? 'selected' : '' }}>E-Wallet</option>
                        </select>
                    </div>

                    {{-- Dropdown Sort --}}
                    <div class="mr-3">
                        <select id="sortFilter" name="sort"
                            class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-gray-400 transition-colors">
                            <option value="">Urutkan</option>
                            <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                            <option value="terlama" {{ request('sort') == 'terlama' ? 'selected' : '' }}>Terlama</option>
                            <option value="terupdate" {{ request('sort') == 'terupdate' ? 'selected' : '' }}>Terupdate
                            </option>
                        </select>
                    </div>

                    {{-- Tombol Tambah --}}
                    <a href="javascript:void(0);" onclick="openMetodeModal('{{ route('metode_pembayaran.create') }}')"
                        class="px-7 py-2 bg-[#560024] text-white font-semibold rounded-lg hover:bg-gray-700 flex items-center justify-center text-sm ml-auto">
                        Tambah
                    </a>
                </form>
            </div>

            {{-- Tabel item --}}
            <div class="overflow-x-auto rounded-lg">
                <table class="w-full table-auto border-collapse text-center">
                    <thead class="bg-[#560024] text-white text-sm">
                        <tr>
                            <th class="p-3">NO</th>
                            <th class="p-3">METODE</th>
                            <th class="p-3">PEMBAYARAN</th>
                            <th class="p-3">KODE BAYAR</th>
                            <th class="p-3">ATAS NAMA</th>
                            <th class="p-3">ACTION</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse ($metode as $item)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3">{{ $metode->firstItem() + $loop->index }}</td>
                                <td class="p-3">{{ $item->metode->nama_metode }}</td>
                                <td class="p-3">{{ $item->nama_pembayaran }}</td>
                                <td class="p-3">{{ $item->kode_bayar }}</td>
                                <td class="p-3">{{ $item->atas_nama }}</td>
                                <td class="p-3 flex gap-2 justify-center items-center">
                                    {{-- Tombol Detail --}}
                                    <button
                                        class="flex items-center justify-center py-2 px-3 rounded-lg border border-gray-400 text-black hover:bg-blue-400 hover:border-blue-400"
                                        onclick="openMetodeModal('{{ route('metode_pembayaran.show', ['id' => $item->metode_pembayaran_id]) }}')">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>

                                    {{-- Tombol Edit --}}
                                    <button
                                        class="flex items-center justify-center py-2 px-3 rounded-lg border border-gray-400 text-black hover:bg-yellow-300 hover:border-yellow-300"
                                        onclick="openMetodeModal('{{ route('metode_pembayaran.edit', $item->metode_pembayaran_id) }}')">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </button>

                                    {{-- Tombol Hapus pakai SweetAlert --}}
                                    <button type="button"
                                        onclick="deleteMetode('{{ route('metode_pembayaran.destroy', $item->metode_pembayaran_id) }}')"
                                        class="flex items-center justify-center py-2 px-3 rounded-lg border border-gray-400 text-black hover:bg-red-500 hover:border-red-500">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-3 text-center text-gray-500">item tidak ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-4 flex justify-center space-x-1">
                @if ($metode->onFirstPage() === false)
                    <a href="{{ $metode->previousPageUrl() }}"
                        class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">&laquo;</a>
                @endif

                @foreach ($metode->getUrlRange(1, $metode->lastPage()) as $page => $url)
                    <a href="{{ $url }}"
                        class="px-3 py-1 rounded 
                {{ $metode->currentPage() === $page ? 'bg-[#560024] text-white' : 'bg-gray-200 hover:bg-gray-300' }}">
                        {{ $page }}
                    </a>
                @endforeach

                @if ($metode->hasMorePages())
                    <a href="{{ $metode->nextPageUrl() }}"
                        class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">&raquo;</a>
                @endif
            </div>
        </div>

        {{-- Modal --}}
        <div id="MetodeModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div id="modalContent" class="mx-2 w-full max-w-lg">
                {{-- Konten show.blade.php akan dimuat di sini --}}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // --- Buka modal ---
        function openMetodeModal(url) {
            fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.text())
                .then(html => {
                    document.getElementById('modalContent').innerHTML = html;
                    document.getElementById('MetodeModal').classList.remove('hidden');

                    // Pasang listener form edit setelah modal dimuat
                    const form = document.getElementById('editMetodeForm');
                    if (form) {
                        form.addEventListener('submit', function(e) {
                            e.preventDefault();
                            const url = this.action;
                            const formData = new FormData(this);

                            fetch(url, {
                                    method: 'POST', // tetap pakai POST
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json',
                                        'X-Requested-With': 'XMLHttpRequest'
                                    },
                                    body: formData
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: data.message,
                                            toast: true,
                                            position: 'top-end',
                                            timer: 1500,
                                            showConfirmButton: false
                                        }).then(() => {
                                            // reload halaman setelah alert selesai
                                            window.location.reload();
                                        });

                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Gagal',
                                            text: data.message,
                                            toast: true,
                                            position: 'top-end'
                                        });
                                    }
                                })
                                .catch(err => {
                                    closeModal();
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: err.message || 'Terjadi kesalahan',
                                        toast: true,
                                        position: 'top-end'
                                    });
                                });
                        });
                    }

                    // Listener form create jika ada
                    const createForm = document.getElementById('createMetodeForm');
                    if (createForm) {
                        createForm.addEventListener('submit', handleCreateSubmit);
                    }
                })
                .catch(err => console.error(err));
        }

        // --- Tutup modal ---
        function closeModal() {
            document.getElementById('MetodeModal').classList.add('hidden');
            document.getElementById('modalContent').innerHTML = '';
        }

        // --- Delete metode pembayaran ---
        function deleteMetode(url) {
            Swal.fire({
                title: 'Hapus Metode Pembayaran?',
                text: "Data tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(url, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: data.message,
                                    toast: true,
                                    position: 'top-end',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                // Hapus row dari tabel
                                document.querySelector(`[data-metode-id='${data.id}']`)?.remove();
                            } else {
                                Swal.fire('Gagal', data.message, 'error');
                            }
                        })
                        .catch(err => Swal.fire('Error', 'Terjadi kesalahan', 'error'));
                }
            });
        }

        // --- Tambahkan atribut data-metode-id pada row tabel ---
        document.querySelectorAll('tbody tr').forEach(tr => {
            const metodeId = tr.querySelector('td')?.innerText;
            if (metodeId) tr.setAttribute('data-metode-id', metodeId.trim());
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const metodeFilter = document.getElementById('metodeFilter');
            const sortFilter = document.getElementById('sortFilter');
            const form = document.getElementById('filterForm');

            metodeFilter.addEventListener('change', function() {
                form.submit();
            });

            sortFilter.addEventListener('change', function() {
                form.submit();
            });
        });
    </script>
@endpush
