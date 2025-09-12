@extends('admin.layouts.app')

@section('content')
    <div class="container bg-white rounded-lg min-h-screen">
        <div class="p-8 bg-white rounded-lg shadow">
            {{-- Judul --}}
            <div class="flex justify-between items-start mb-7 border-b border-gray-300 pb-4">
                <h1 class="text-2xl font-bold pl-4 pt-4">Kelola Ukuran</h1>
            </div>

            {{-- Search & Sort --}}
            <div class="flex justify-between items-center mb-7 mt-6">
                <div class="flex items-center gap-3 w-2/3">
                    <form method="GET" action="{{ route('ukuran.index') }}"
                        class="flex items-center border rounded-lg px-3 py-2 w-1/2">
                        <input type="text" name="search" placeholder="Cari Nama Ukuran" value="{{ $searchQuery ?? '' }}"
                            class="w-full outline-none placeholder:text-sm">
                        <button type="submit" class="ml-2">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>

                    {{-- Dropdown Sort --}}
                    <form method="GET" action="{{ route('ukuran.index') }}">
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
                    <a href="javascript:void(0);" onclick="openUkuranModal('{{ route('ukuran.create') }}')"
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
                            <th class="p-3">UKURAN</th>
                            <th class="p-3">DESKRIPSI</th>
                            <th class="p-3">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse ($ukuran as $item)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3">{{ $ukuran->firstItem() + $loop->index }}</td>
                                <td class="p-3">{{ $item->nama_ukuran }}</td>
                                <td class="p-3 break-words max-w-xs">{{ $item->deskripsi }}</td>
                                <td class="p-3 flex gap-2 justify-center items-center">
                                    {{-- Tombol Detail --}}
                                    <button
                                        class="flex items-center justify-center py-2 px-3 rounded-lg border border-gray-400 text-black hover:bg-blue-400 hover:border-blue-400"
                                        onclick="openUkuranModal('{{ route('ukuran.show', ['id' => $item->ukuran_id]) }}')">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>

                                    {{-- Tombol Edit --}}
                                    <button
                                        class="flex items-center justify-center py-2 px-3 rounded-lg border border-gray-400 text-black hover:bg-yellow-300 hover:border-yellow-300"
                                        onclick="openUkuranModal('{{ route('ukuran.edit', ['id' => $item->ukuran_id]) }}')">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </button>

                                    {{-- Tombol Hapus --}}
                                    <button
                                        class="flex items-center justify-center py-2 px-3 rounded-lg border border-gray-400 text-black hover:bg-red-500 hover:border-red-500"
                                        onclick="deleteUkuran('{{ route('ukuran.destroy', $item->ukuran_id) }}')">
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
                @if ($ukuran->onFirstPage() === false)
                    <a href="{{ $ukuran->previousPageUrl() }}"
                        class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">&laquo;</a>
                @endif

                @foreach ($ukuran->getUrlRange(1, $ukuran->lastPage()) as $page => $url)
                    <a href="{{ $url }}"
                        class="px-3 py-1 rounded 
                {{ $ukuran->currentPage() === $page ? 'bg-[#560024] text-white' : 'bg-gray-200 hover:bg-gray-300' }}">
                        {{ $page }}
                    </a>
                @endforeach

                @if ($ukuran->hasMorePages())
                    <a href="{{ $ukuran->nextPageUrl() }}"
                        class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">&raquo;</a>
                @endif
            </div>
        </div>

        {{-- Modal --}}
        <div id="UkuranModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div id="modalContent" class="mx-2 w-full max-w-lg">
                {{-- Konten show.blade.php akan dimuat di sini --}}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function sortTable(column) {
            const url = new URL(window.location.href);
            const currentSort = url.searchParams.get('sort');
            const currentDir = url.searchParams.get('direction');

            let newDir = 'asc';
            if (currentSort === column && currentDir === 'asc') newDir = 'desc';

            url.searchParams.set('sort', column);
            url.searchParams.set('direction', newDir);

            window.location.href = url.toString();
        }
    </script>
    <script>
        // --- Buka modal ---
        function openUkuranModal(url) {
            fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.text())
                .then(html => {
                    document.getElementById('modalContent').innerHTML = html;
                    document.getElementById('UkuranModal').classList.remove('hidden');

                    // Pasang listener form setelah modal dimuat
                    const form = document.getElementById('editUkuranForm');
                    if (form) {
                        form.addEventListener('submit', function(e) {
                            e.preventDefault();
                            const url = this.action;
                            const formData = new FormData(this);
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
                                    closeModal(); // Tutup modal

                                    if (data.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: data.message,
                                            toast: true,
                                            position: 'top-end',
                                            timer: 1500,
                                            showConfirmButton: false
                                        });

                                        // Update row tabel otomatis (loop semua field dari data.data)
                                        const row = document.querySelector(
                                            `[data-ukuran-id='${data.data.ukuran_id}']`);
                                        if (row) {
                                            Object.keys(data.data).forEach((key, index) => {
                                                // index +1 karena td pertama biasanya ID
                                                if (row.querySelector(`td:nth-child(${index + 1})`))
                                                    row.querySelector(`td:nth-child(${index + 1})`)
                                                    .textContent = data.data[key];
                                            });
                                        }

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

                    // Pasang listener form create setelah modal dimuat
                    const createForm = document.getElementById('createUkuranForm');
                    if (createForm) {
                        createForm.addEventListener('submit', handleCreateSubmit);
                    }
                })
                .catch(err => console.error(err));
        }

        // --- Tutup modal ---
        function closeModal() {
            document.getElementById('UkuranModal').classList.add('hidden');
            document.getElementById('modalContent').innerHTML = '';
        }

        // --- Delete bahan ---
        function deleteUkuran(url) {
            Swal.fire({
                title: 'Hapus Ukuran?',
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
                                document.querySelector(`[data-ukuran-id='${data.id}']`)?.remove();
                            } else {
                                Swal.fire('Gagal', data.message, 'error');
                            }
                        })
                        .catch(err => Swal.fire('Error', 'Terjadi kesalahan', 'error'));
                }
            });
        }

        // --- Tambahkan atribut data-bahan-id ---
        document.querySelectorAll('tbody tr').forEach(tr => {
            const ukuranId = tr.querySelector('td')?.innerText;
            if (ukuranId) tr.setAttribute('data-ukuran-id', ukuranId.trim());
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sortFilter = document.getElementById('sortFilter');

            sortFilter.addEventListener('change', function() {
                this.form.submit();
            });
        });
    </script>
@endpush
