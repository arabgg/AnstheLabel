@extends('admin.layouts.app')

@section('content')
    <div class="container bg-white rounded-lg min-h-screen">
        <div class="p-8 bg-white rounded-lg shadow">
            {{-- Judul --}}
            <div class="flex justify-between items-start mb-7 border-b border-gray-300 pb-4">
                <h1 class="text-2xl font-bold pl-4 pt-4">Kelola Voucher</h1>
            </div>

            {{-- Search & Sort --}}
            <div class="flex justify-between items-center mb-7 mt-6">
                <div class="flex items-center gap-3 w-2/3">
                    <form method="GET" action="{{ route('voucher.index') }}"
                        class="flex items-center border rounded-lg px-3 py-2 w-1/2">
                        <input type="text" name="search" placeholder="Cari Kode Voucher" value="{{ $searchQuery ?? '' }}"
                            class="w-full outline-none placeholder:text-sm">
                        <button type="submit" class="ml-2">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>

                    {{-- Dropdown Sort --}}
                    <form method="GET" action="{{ route('voucher.index') }}">
                        <select id="sortFilter" name="sort"
                            class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-gray-400 transition-colors">
                            <option value="">Urutkan</option>
                            <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                            <option value="terlama" {{ request('sort') == 'terlama' ? 'selected' : '' }}>Terlama</option>
                        </select>
                    </form>
                </div>

                <a href="javascript:void(0);" onclick="openVoucherModal('{{ route('voucher.create') }}')"
                    class="px-7 py-2 bg-[#560024] text-white font-semibold rounded-lg hover:bg-gray-700 flex items-center justify-center text-sm">
                    Tambah
                </a>

            </div>

            {{-- Tabel item --}}
            <div class="overflow-x-auto rounded-lg">
                <table class="w-full table-auto border-collapse text-center">
                    <thead class="bg-[#560024] text-white text-sm">
                        <tr>
                            <th class="p-3">NO</th>
                            <th class="p-3">NAMA VOUCHER</th>
                            <th class="p-3">TIPE DISKON</th>
                            <th class="p-3">NILAI DISKON</th>
                            <th class="p-3">STATUS</th>
                            <th class="p-3">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse ($voucher as $item)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3">{{ $voucher->firstItem() + $loop->index }}</td>
                                <td class="p-3">{{ $item->kode_voucher }}</td>
                                <td class="p-3">{{ ucfirst($item->tipe_diskon) }}</td>
                                <td class="p-3">
                                    @if ($item->tipe_diskon === 'persen')
                                        {{ $item->nilai_diskon * 100 }}%
                                    @else
                                        Rp {{ number_format($item->nilai_diskon, 0, ',', '.') }}
                                    @endif
                                </td>
                                <td class="p-3">
                                    @if ($item->status_voucher == 1)
                                        <div
                                            class="border-transparent p-2 rounded-lg bg-green-200 text-green-800 border-green-400 font-semibold text-center">Aktif</div>
                                    @else
                                        <div class="border-transparent p-2 rounded-lg bg-red-200 text-red-800 border-red-400 font-semibold text-center">Nonaktif</div>
                                    @endif
                                </td>
                                <td class="p-3 flex gap-2 justify-center items-center">
                                    {{-- Tombol Detail --}}
                                    <button
                                        class="flex items-center justify-center py-2 px-3 rounded-lg border border-gray-400 text-black hover:bg-blue-400 hover:border-blue-400"
                                        onclick="openVoucherModal('{{ route('voucher.show', ['id' => $item->voucher_id]) }}')">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>

                                    {{-- Tombol Edit --}}
                                    <button
                                        class="flex items-center justify-center py-2 px-3 rounded-lg border border-gray-400 text-black hover:bg-yellow-300 hover:border-yellow-300"
                                        onclick="openVoucherModal('{{ route('voucher.edit', ['id' => $item->voucher_id]) }}')">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </button>

                                    {{-- Tombol Hapus --}}
                                    <button
                                        class="flex items-center justify-center py-2 px-3 rounded-lg border border-gray-400 text-black hover:bg-red-500 hover:border-red-500"
                                        onclick="deleteVoucher('{{ route('voucher.destroy', $item->voucher_id) }}')">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-3 text-center text-gray-500">Item tidak ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-4 flex justify-center space-x-1">
                @if ($voucher->onFirstPage() === false)
                    <a href="{{ $voucher->previousPageUrl() }}"
                        class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">&laquo;</a>
                @endif

                @foreach ($voucher->getUrlRange(1, $voucher->lastPage()) as $page => $url)
                    <a href="{{ $url }}"
                        class="px-3 py-1 rounded 
                {{ $voucher->currentPage() === $page ? 'bg-[#560024] text-white' : 'bg-gray-200 hover:bg-gray-300' }}">
                        {{ $page }}
                    </a>
                @endforeach

                @if ($voucher->hasMorePages())
                    <a href="{{ $voucher->nextPageUrl() }}"
                        class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">&raquo;</a>
                @endif
            </div>
        </div>

        {{-- Modal --}}
        <div id="VoucherModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div id="modalContent" class="mx-2 w-full max-w-lg">
                {{-- Konten show.blade.php akan dimuat di sini --}}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function openVoucherModal(url) {
            fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.text())
                .then(html => {
                    document.getElementById('modalContent').innerHTML = html;
                    document.getElementById('VoucherModal').classList.remove('hidden');

                    // Event listener untuk form edit
                    const editForm = document.getElementById('editVoucherForm');
                    if (editForm) {
                        editForm.addEventListener('submit', handleEditSubmit);
                    }

                    // Event listener untuk form create
                    const createForm = document.getElementById('createVoucherForm');
                    if (createForm) {
                        createForm.addEventListener('submit', handleCreateSubmit);
                    }
                })
                .catch(err => {
                    Swal.fire('Error', err.message || 'Gagal memuat modal', 'error');
                });
        }

        function closeModal() {
            document.getElementById('VoucherModal').classList.add('hidden');
            document.getElementById('modalContent').innerHTML = '';
        }

        function handleEditSubmit(e) {
            e.preventDefault();
            const form = e.target;
            const url = form.action;
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());

            fetch(url, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(data)
                })
                .then(res => res.json())
                .then(data => {
                    closeModal();
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: data.message,
                            toast: true,
                            position: 'top-end',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => location.reload());

                        // Update row tabel
                        const row = document.querySelector(`[data-voucher-id='${data.data.voucher_id}']`);
                        if (row) {
                            const values = Object.values(data.data);
                            row.querySelectorAll('td').forEach((td, idx) => {
                                if (values[idx] !== undefined) td.textContent = values[idx];
                            });
                        }
                    } else {
                        Swal.fire('Gagal', data.message, 'error');
                    }
                })
                .catch(err => {
                    closeModal();
                    Swal.fire('Error', err.message || 'Terjadi kesalahan', 'error');
                });
        }

        function handleCreateSubmit(e) {
            e.preventDefault();
            const form = e.target;
            const url = form.action;
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(data)
                })
                .then(res => res.json())
                .then(data => {
                    closeModal();
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: data.message,
                            toast: true,
                            position: 'top-end',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => location.reload());
                        // TODO: Tambahkan row baru ke tabel (jika ingin langsung update tanpa reload)
                    } else {
                        Swal.fire('Gagal', data.message, 'error');
                    }
                })
                .catch(err => {
                    closeModal();
                    Swal.fire('Error', err.message || 'Terjadi kesalahan', 'error');
                });
        }

        function deleteVoucher(url) {
            Swal.fire({
                title: 'Hapus Voucher?',
                text: "Data tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then(result => {
                if (result.isConfirmed) {
                    fetch(url, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
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
                                }).then(() => location.reload());
                                document.querySelector(`[data-voucher-id='${data.id}']`)?.remove();
                            } else {
                                Swal.fire('Gagal', data.message, 'error');
                            }
                        })
                        .catch(err => Swal.fire('Error', err.message || 'Terjadi kesalahan', 'error'));
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Tambahkan data-voucher-id ke setiap row tabel
            document.querySelectorAll('tbody tr').forEach(tr => {
                const voucherId = tr.querySelector('td')?.innerText;
                if (voucherId) tr.setAttribute('data-voucher-id', voucherId.trim());
            });

            // Listener sort filter (dropdown)
            const sortFilter = document.getElementById('sortFilter');
            if (sortFilter) {
                sortFilter.addEventListener('change', function() {
                    this.form.submit();
                });
            }
        });
    </script>
@endpush
