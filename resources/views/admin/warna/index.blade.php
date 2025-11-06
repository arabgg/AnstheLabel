@extends('admin.layouts.app')

@section('content')
    <div class="container bg-white rounded-lg min-h-screen">
        <div class="p-8 bg-white rounded-lg shadow">
            {{-- Judul --}}
            <div class="flex justify-between items-start mb-7 border-b border-gray-300 pb-4">
                <h1 class="text-2xl font-bold pl-4 pt-4">Kelola Warna</h1>
            </div>

            {{-- Search --}}
            <div class="flex justify-between items-center mb-7 mt-6">
                <div class="flex items-center gap-3 w-2/3">
                    <form method="GET" action="{{ route('warna.index') }}"
                        class="flex items-center border rounded-lg px-3 py-2 w-1/2">
                        <input type="text" name="search" placeholder="Cari Nama Warna" value="{{ $searchQuery ?? '' }}"
                            class="w-full outline-none placeholder:text-sm">
                        <button type="submit" class="ml-2">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>

                    {{-- Dropdown Sort --}}
                    <form method="GET" action="{{ route('warna.index') }}">
                        <select id="sortFilter" name="sort"
                            class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-gray-400 transition-colors">
                            <option value="">Urutkan</option>
                            <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                            <option value="terlama" {{ request('sort') == 'terlama' ? 'selected' : '' }}>Terlama</option>
                            </option>
                        </select>
                    </form>
                </div>

                {{-- Tombol Tambah --}}
                <a href="javascript:void(0);" onclick="openWarnaModal('{{ route('warna.create') }}')"
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
                            <th class="p-3">NAMA WARNA</th>
                            <th class="p-3 text-center align-middle">KODE HEX</th>
                            <th class="p-3">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse ($warna as $item)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3">{{ $warna->firstItem() + $loop->index }}</td>
                                <td class="p-3">{{ $item->nama_warna }}</td>
                                <td class="p-3 align-middle">
                                    <div class="flex items-center justify-center gap-2 w-full">
                                        <div class="w-4 h-4 rounded-full border"
                                            style="background-color: {{ $item->kode_hex }};"></div>
                                        <span class="inline-block">{{ $item->kode_hex }}</span>
                                    </div>
                                </td>
                                <td class="p-3 flex gap-2 justify-center items-center">
                                    {{-- Tombol Detail --}}
                                    <button
                                        class="flex items-center justify-center py-2 px-3 rounded-lg border border-gray-400 text-black hover:bg-blue-400 hover:border-blue-400"
                                        onclick="openWarnaModal('{{ route('warna.show', ['id' => $item->warna_id]) }}')">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>

                                    {{-- Tombol Edit --}}
                                    <button
                                        class="flex items-center justify-center py-2 px-3 rounded-lg border border-gray-400 text-black hover:bg-yellow-300 hover:border-yellow-300"
                                        onclick="openWarnaModal('{{ route('warna.edit', ['id' => $item->warna_id]) }}')">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </button>

                                    {{-- Tombol Hapus --}}
                                    <button
                                        class="flex items-center justify-center py-2 px-3 rounded-lg border border-gray-400 text-black hover:bg-red-500 hover:border-red-500"
                                        onclick="deleteWarna('{{ route('warna.destroy', $item->warna_id) }}')">
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
                @if ($warna->onFirstPage() === false)
                    <a href="{{ $warna->previousPageUrl() }}"
                        class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">&laquo;</a>
                @endif

                @foreach ($warna->getUrlRange(1, $warna->lastPage()) as $page => $url)
                    <a href="{{ $url }}"
                        class="px-3 py-1 rounded 
                {{ $warna->currentPage() === $page ? 'bg-[#560024] text-white' : 'bg-gray-200 hover:bg-gray-300' }}">
                        {{ $page }}
                    </a>
                @endforeach

                @if ($warna->hasMorePages())
                    <a href="{{ $warna->nextPageUrl() }}"
                        class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">&raquo;</a>
                @endif
            </div>
        </div>

        {{-- Modal --}}
        <div id="WarnaModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
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
        function openWarnaModal(url) {
            fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.text())
                .then(html => {
                    document.getElementById('modalContent').innerHTML = html;
                    document.getElementById('WarnaModal').classList.remove('hidden');

                    // Listener form edit
                    const editForm = document.getElementById('editWarnaForm');
                    if (editForm) {
                        editForm.addEventListener('submit', handleEditSubmit);
                    }

                    // Listener form create
                    const createForm = document.getElementById('createWarnaForm');
                    if (createForm) {
                        createForm.addEventListener('submit', handleCreateSubmit);
                    }
                })
                .catch(err => Swal.fire('Error', err.message || 'Gagal memuat modal', 'error'));
        }

        // --- Tutup modal ---
        function closeModal() {
            document.getElementById('WarnaModal').classList.add('hidden');
            document.getElementById('modalContent').innerHTML = '';
        }

        // --- Handle edit warna ---
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
                    } else {
                        Swal.fire('Gagal', data.message, 'error');
                    }
                })
                .catch(err => {
                    closeModal();
                    Swal.fire('Error', err.message || 'Terjadi kesalahan', 'error');
                });
        }

        // --- Handle create warna ---
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
                    } else {
                        Swal.fire('Gagal', data.message, 'error');
                    }
                })
                .catch(err => {
                    closeModal();
                    Swal.fire('Error', err.message || 'Terjadi kesalahan', 'error');
                });
        }

        // --- Delete warna ---
        function deleteWarna(url) {
            Swal.fire({
                title: 'Hapus Warna?',
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
                            } else {
                                Swal.fire('Gagal', data.message, 'error');
                            }
                        })
                        .catch(err => Swal.fire('Error', err.message || 'Terjadi kesalahan', 'error'));
                }
            });
        }

        // --- DOM Ready ---
        document.addEventListener('DOMContentLoaded', function() {
            // Tambahkan data-warna-id ke setiap row tabel
            document.querySelectorAll('tbody tr').forEach(tr => {
                const warnaId = tr.querySelector('td')?.innerText;
                if (warnaId) tr.setAttribute('data-warna-id', warnaId.trim());
            });

            // Listener sort filter
            const sortFilter = document.getElementById('sortFilter');
            if (sortFilter) {
                sortFilter.addEventListener('change', function() {
                    this.form.submit();
                });
            }
        });
    </script>
@endpush
