@extends('admin.layouts.app')

@section('content')
<div class="p-8 bg-white rounded-lg shadow">
    {{-- Judul --}}
    <div class="flex justify-between items-start mb-7 border-b border-gray-300 pb-4">
        <h1 class="text-2xl font-bold pl-4 pt-4">Manage Kategori</h1>
    </div>

    {{-- Search --}}
    <div class="flex justify-end mb-7 mt-12">
        <form method="GET" action="{{ route('kategori.index') }}" class="mr-3 flex items-center border rounded-lg px-3 py-2 w-1/3">
            <input type="text" name="search" placeholder="Search Kategori" value="{{ $searchQuery ?? '' }}" class="w-full outline-none placeholder:text-sm">
            <button type="submit" class="ml-2">
                <i class="fas fa-search"></i>
            </button>
        </form>
        
        <a href="{{ url('/kategori/create') }}"
        class="px-7 py-2 bg-[#560024] text-white font-semibold rounded-lg hover:bg-gray-700 flex items-center justify-center text-sm">
            Tambah
        </a>
    </div>

    {{-- Tabel item --}}
    <div class="overflow-x-auto rounded-lg">
        <table class="w-full table-auto border-collapse text-center">
            <thead class="bg-[#560024] text-white text-sm">
                <tr>
                    <th class="p-3">ID</th>
                    <th class="p-3">NAMA KATEGORI</th>
                    <th class="p-3">DIBUAT</th>
                    <th class="p-3">UPDATE</th>
                    <th class="p-3">ACTION</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse ($kategori as $item)
                    <tr class="border-b hover:bg-gray-50" >
                        <td class="p-3">{{ $item->kategori_id }}</td>
                        <td class="p-3">{{ $item->nama_kategori }}</td>
                        <td class="p-3">{{ $item->created_at->format('d M Y [ H : i ]') }}</td>
                        <td class="p-3">{{ $item->updated_at->format('d M Y [ H : i ]') }}</td>
                        <td class="p-3 mt-5 flex gap-2 justify-center items-center">
                            {{-- Tombol Detail --}}
                            <button 
                                class="flex items-center justify-center py-2 px-3 rounded-lg border border-gray-400 text-black hover:bg-blue-400 hover:border-blue-400"
                                onclick="openKategoriModal('{{ route('kategori.show', ['id' => $item->kategori_id]) }}')">
                                <i class="fa-solid fa-database"></i>
                            </button>

                            {{-- Tombol Edit --}}
                            <button
                                class="flex items-center justify-center py-2 px-3 rounded-lg border border-gray-400 text-black hover:bg-yellow-300 hover:border-yellow-300"
                                onclick="openKategoriModal('{{ route('kategori.edit', ['id' => $item->kategori_id]) }}')">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </button>

                            {{-- Tombol Hapus --}}
                            <button 
                                class="flex items-center justify-center py-2 px-3 rounded-lg border border-gray-400 text-black hover:bg-red-500 hover:border-red-500"
                                onclick="deleteKategori('{{ route('kategori.destroy', $item->kategori_id) }}')">
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
        @if ($kategori->onFirstPage() === false)
            <a href="{{ $kategori->previousPageUrl() }}" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">&laquo;</a>
        @endif

        @foreach ($kategori->getUrlRange(1, $kategori->lastPage()) as $page => $url)
            <a href="{{ $url }}" class="px-3 py-1 rounded 
                {{ $kategori->currentPage() === $page ? 'bg-[#560024] text-white' : 'bg-gray-200 hover:bg-gray-300' }}">
                {{ $page }}
            </a>
        @endforeach

        @if ($kategori->hasMorePages())
            <a href="{{ $kategori->nextPageUrl() }}" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">&raquo;</a>
        @endif
    </div>
</div>

{{-- Modal --}}
<div id="KategoriModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div id="modalContent" class="mx-2 w-full max-w-lg">
        {{-- Konten ajax akan dimuat di sini --}}
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Fungsi buka modal
function openKategoriModal(url) {
    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(res => res.text())
        .then(html => {
            document.getElementById('modalContent').innerHTML = html;
            document.getElementById('KategoriModal').classList.remove('hidden');

            // Pasang listener form edit setelah modal dimuat
            const form = document.getElementById('editKategoriForm');
            if(form){
                form.addEventListener('submit', function(e){
                    e.preventDefault();
                    const url = this.action;
                    const data = { nama_kategori: this.nama_kategori.value };

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
                        closeModal(); // Tutup modal dulu

                        // SweetAlert sukses
                        if(data.success){
                            Swal.fire({
                                icon: 'success',
                                title: data.message,
                                toast: true,
                                position: 'top-end',
                                timer: 1500,
                                showConfirmButton: false
                            });

                            // Update nama kategori di tabel
                            const row = document.querySelector(`[data-kategori-id='${data.data.kategori_id}']`);
                            if(row) row.querySelector('td:nth-child(2)').textContent = data.data.nama_kategori;
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
        })
        .catch(err => console.error(err));
}

// Fungsi tutup modal
function closeModal() {
    document.getElementById('KategoriModal').classList.add('hidden');
    document.getElementById('modalContent').innerHTML = '';
}

// Delete kategori
function deleteKategori(url) {
    Swal.fire({
        title: 'Hapus Kategori?',
        text: "Data tidak bisa dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if(result.isConfirmed){
            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if(data.success){
                    Swal.fire({
                        icon: 'success',
                        title: data.message,
                        toast: true,
                        position: 'top-end',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    // Hapus row dari tabel
                    document.querySelector(`[data-kategori-id='${data.id}']`)?.remove();
                } else {
                    Swal.fire('Gagal', data.message, 'error');
                }
            })
            .catch(err => Swal.fire('Error', 'Terjadi kesalahan', 'error'));
        }
    });
}

// Tambahkan atribut data-kategori-id ke setiap baris tabel
document.querySelectorAll('tbody tr').forEach(tr => {
    const kategoriId = tr.querySelector('td')?.innerText;
    if(kategoriId) tr.setAttribute('data-kategori-id', kategoriId.trim());
});
</script>
@endpush
