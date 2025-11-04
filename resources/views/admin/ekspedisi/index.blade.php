@extends('admin.layouts.app')

@section('content')
    <div class="container bg-white rounded-lg min-h-screen">
        <div class="p-8 bg-white rounded-lg shadow">
            {{-- Judul --}}
            <div class="flex justify-between items-start mb-7 border-b border-gray-300 pb-4">
                <h1 class="text-2xl font-bold pl-4 pt-4">Kelola Ekspedisi</h1>
            </div>

            {{-- Search & Filter Form --}}
            <div class="flex justify-between items-center mb-7 mt-6">
                <div class="flex items-center gap-3 w-2/3">
                    {{-- Form Search --}}
                    <form method="GET" action="{{ route('ekspedisi.index') }}"
                        class="flex items-center border rounded-lg px-3 py-2 w-1/2">
                        <input type="text" name="search" placeholder="Cari Ekspedisi..." value="{{ request('search') }}"
                            class="w-full outline-none placeholder:text-sm">
                        <button type="submit" class="ml-2">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>

                    {{-- Dropdown Sort --}}
                    <form method="GET" action="{{ route('ekspedisi.index') }}">
                        <select id="sortFilter" name="sort"
                            class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-gray-400 transition-colors"
                            onchange="this.form.submit()">
                            <option value="">Urutkan</option>
                            <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                            <option value="terlama" {{ request('sort') == 'terlama' ? 'selected' : '' }}>Terlama</option>
                        </select>
                    </form>
                </div>

                {{-- Tombol Tambah --}}
                <a href="javascript:void(0);" onclick="openEkspedisiModal('{{ route('ekspedisi.create') }}')"
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
                            <th class="p-3">NAMA EKSPEDISI</th>
                            <th class="p-3">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse ($ekspedisi as $item)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3">{{ $ekspedisi->firstItem() + $loop->index }}</td>
                                <td class="p-3">{{ $item->nama_ekspedisi }}</td>
                                <td class="p-3 flex gap-2 justify-center items-center">
                                    {{-- Tombol Detail --}}
                                    <button
                                        class="flex items-center justify-center py-2 px-3 rounded-lg border border-gray-400 text-black hover:bg-blue-400 hover:border-blue-400"
                                        onclick="openEkspedisiModal('{{ route('ekspedisi.show', ['id' => $item->ekspedisi_id]) }}')">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>

                                    {{-- Tombol Edit --}}
                                    <button
                                        class="flex items-center justify-center py-2 px-3 rounded-lg border border-gray-400 text-black hover:bg-yellow-300 hover:border-yellow-300"
                                        onclick="openEkspedisiModal('{{ route('ekspedisi.edit', $item->ekspedisi_id) }}')">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </button>

                                    {{-- Tombol Hapus pakai SweetAlert --}}
                                    <button type="button"
                                        onclick="deleteEkspedisi('{{ route('ekspedisi.destroy', $item->ekspedisi_id) }}')"
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
                @if ($ekspedisi->onFirstPage() === false)
                    <a href="{{ $ekspedisi->previousPageUrl() }}"
                        class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">&laquo;</a>
                @endif

                @foreach ($ekspedisi->getUrlRange(1, $ekspedisi->lastPage()) as $page => $url)
                    <a href="{{ $url }}"
                        class="px-3 py-1 rounded 
                {{ $ekspedisi->currentPage() === $page ? 'bg-[#560024] text-white' : 'bg-gray-200 hover:bg-gray-300' }}">
                        {{ $page }}
                    </a>
                @endforeach

                @if ($ekspedisi->hasMorePages())
                    <a href="{{ $ekspedisi->nextPageUrl() }}"
                        class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">&raquo;</a>
                @endif
            </div>
        </div>

        {{-- Modal --}}
        <div id="EkspedisiModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div id="modalContent" class="mx-2 w-full max-w-lg">
                {{-- Konten show.blade.php akan dimuat di sini --}}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // --- Buka modal ---
        function openEkspedisiModal(url) {
            fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.text())
                .then(html => {
                    document.getElementById('modalContent').innerHTML = html;
                    document.getElementById('EkspedisiModal').classList.remove('hidden');

                    // Listener form edit
                    const editForm = document.getElementById('editEkspedisiForm');
                    if (editForm) {
                        editForm.addEventListener('submit', function(e) {
                            e.preventDefault();
                            const url = this.action;
                            const formData = new FormData(this);

                            fetch(url, {
                                    method: 'POST',
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
                                        closeModal();
                                        Swal.fire({
                                            icon: 'success',
                                            title: data.message,
                                            toast: true,
                                            position: 'top-end',
                                            timer: 1500,
                                            showConfirmButton: false
                                        }).then(() => {
                                            window.location.reload();
                                        });
                                    } else {
                                        Swal.fire('Gagal', data.message, 'error');
                                    }
                                })
                                .catch(err => {
                                    Swal.fire('Error', err.message, 'error');
                                });
                        });
                    }

                    // Listener form create
                    const createForm = document.getElementById('createEkspedisiForm');
                    if (createForm) {
                        createForm.addEventListener('submit', function(e) {
                            e.preventDefault();
                            const url = this.action;
                            const formData = new FormData(this);

                            fetch(url, {
                                    method: 'POST',
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
                                        closeModal();
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Berhasil menambahkan ekspedisi',
                                            toast: true,
                                            position: 'top-end',
                                            timer: 1500,
                                            showConfirmButton: false
                                        }).then(() => {
                                            window.location.reload();
                                        });
                                    } else {
                                        Swal.fire('Gagal', data.message, 'error');
                                    }
                                })
                                .catch(err => {
                                    Swal.fire('Error', err.message, 'error');
                                });
                        });
                    }
                })
                .catch(err => console.error(err));
        }

        // --- Tutup modal ---
        function closeModal() {
            document.getElementById('EkspedisiModal').classList.add('hidden');
            document.getElementById('modalContent').innerHTML = '';
        }

        // --- Delete ekspedisi ---
        function deleteEkspedisi(url) {
            Swal.fire({
                title: 'Hapus Ekspedisi?',
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
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire('Gagal', data.message, 'error');
                            }
                        })
                        .catch(err => Swal.fire('Error', 'Terjadi kesalahan', 'error'));
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
