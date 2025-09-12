@extends('admin.layouts.app')

@section('content')
    <div class="p-8 bg-white rounded-lg shadow">
        {{-- Judul --}}
        <div class="flex justify-between items-start mb-7 border-b border-gray-300 pb-4">
            <h1 class="text-2xl font-bold pl-4 pt-4">Manage Warna</h1>
        </div>

        {{-- Search --}}
        <div class="flex justify-end mb-7 mt-12">
            <form method="GET" action="{{ route('warna.index') }}"
                class="mr-3 flex items-center border rounded-lg px-3 py-2 w-1/3">
                <input type="text" name="search" placeholder="Search Warna" value="{{ $searchQuery ?? '' }}"
                    class="w-full outline-none placeholder:text-sm">
                <button type="submit" class="ml-2">
                    <i class="fas fa-search"></i>
                </button>
            </form>

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
                        @php
                            $columns = [
                                'warna_id' => 'ID',
                                'nama_warna' => 'NAMA WARNA',
                                'kode_hex' => 'KODE WARNA',
                            ];
                        @endphp
                        @foreach ($columns as $col => $label)
                            <th class="p-3 cursor-pointer" onclick="sortTable('{{ $col }}')">
                                {{ $label }}
                                @if (request('sort') === $col)
                                    @if (request('direction') === 'asc')
                                        <i class="fas fa-arrow-up ml-1"></i>
                                    @else
                                        <i class="fas fa-arrow-down ml-1"></i>
                                    @endif
                                @else
                                    <i class="fas fa-sort ml-1"></i>
                                @endif
                            </th>
                        @endforeach

                        <th class="p-3">ACTION</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse ($warna as $item)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3">{{ $warna->firstItem() + $loop->index }}</td>
                            <td class="p-3">{{ $item->nama_warna }}</td>
                            <td class="p-3">
                                <div class="flex items-center gap-2">
                                    <div class="w-4 h-4 rounded-full border"
                                        style="background-color: {{ $item->kode_hex }};"></div>
                                    <span>{{ $item->kode_hex }}</span>
                                </div>
                            </td>
                            <td class="p-3 mt-5 flex gap-2 justify-center items-center">
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
                <a href="{{ $warna->nextPageUrl() }}" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">&raquo;</a>
            @endif
        </div>
    </div>

    {{-- Modal --}}
    <div id="WarnaModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div id="modalContent" class="mx-2 w-full max-w-lg">
            {{-- Konten show.blade.php akan dimuat di sini --}}
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

                    // Pasang listener form setelah modal dimuat
                    const form = document.getElementById('editWarnaForm');
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
                                            `[data-warna-id='${data.data.warna_id}']`);
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
                    const createForm = document.getElementById('createWarnaForm');
                    if (createForm) {
                        createForm.addEventListener('submit', handleCreateSubmit);
                    }
                })
                .catch(err => console.error(err));
        }

        // --- Tutup modal ---
        function closeModal() {
            document.getElementById('WarnaModal').classList.add('hidden');
            document.getElementById('modalContent').innerHTML = '';
        }

        // --- Delete bahan ---
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
                                document.querySelector(`[data-warna-id='${data.id}']`)?.remove();
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
            const warnaId = tr.querySelector('td')?.innerText;
            if (warnaId) tr.setAttribute('data-warna-id', warnaId.trim());
        });
    </script>
@endpush
