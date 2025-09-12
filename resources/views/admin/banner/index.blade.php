@extends('admin.layouts.app')

@section('content')
    <div class="container bg-white rounded-lg min-h-screen">
        <div class="p-8 bg-white rounded-lg shadow">
            {{-- Judul --}}
            <div class="flex justify-between items-start mb-7 border-b border-gray-300 pb-4">
                <h1 class="text-2xl font-bold pl-4 pt-4">Manage Banner</h1>
            </div>

            {{-- Search --}}
            <div class="flex mb-5">
                <form method="GET" action="{{ route('banner.index') }}"
                    class="flex items-center border rounded-lg px-3 py-2 w-1/3">
                    <input type="text" name="search" placeholder="Search Banner" value="{{ $searchQuery ?? '' }}"
                        class="w-full outline-none placeholder:text-sm">
                    <button type="submit" class="ml-2">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>

            {{-- Tabel Banner --}}
            <div class="overflow-x-auto rounded-lg">
                <table class="w-full table-auto border-collapse text-center">
                    <thead class="bg-[#560024] text-white text-sm">
                        <tr>
                            <th class="p-3">NO</th>
                            <th class="p-3">NAMA</th>
                            <th class="p-3">FILE FOTO</th>
                            <th class="p-3">DESKRIPSI</th>
                            <th class="p-3">ACTION</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse ($banners as $banner)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3">{{ $loop->iteration }}</td>
                                <td class="p-3">{{ $banner->nama_banner }}</td>
                                <td class="p-3">
                                    <div class="flex justify-center">
                                        @if ($banner->foto_banner)
                                            @if ($banner->is_video)
                                                <video class="w-36 h-20 object-cover rounded" controls>
                                                    <source src="{{ asset('storage/banner/' . $banner->foto_banner) }}"
                                                        type="video/mp4">
                                                </video>
                                            @else
                                                <img src="{{ asset('storage/banner/' . $banner->foto_banner) }}"
                                                    alt="{{ $banner->nama_banner }}" class="w-36 h-20 object-cover rounded">
                                            @endif
                                        @else
                                            <span class="text-gray-400">No Media</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="p-3 break-words max-w-xs">{{ $banner->deskripsi }}</td>
                                <td class="p-3 mt-5 flex gap-2 justify-center items-center">
                                    {{-- Tombol Detail --}}
                                    <button
                                        class="flex items-center justify-center py-2 px-3 rounded-lg border border-gray-400 text-black hover:bg-blue-400 hover:border-blue-400"
                                        onclick="openBannerModal('{{ route('banner.show', ['id' => $banner->banner_id]) }}')">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>

                                    {{-- Tombol Edit (AJAX) --}}
                                    <button
                                        class="flex items-center justify-center py-2 px-3 rounded-lg border border-gray-400 text-black hover:bg-yellow-300 hover:border-yellow-300"
                                        onclick="openBannerModal('{{ route('banner.edit', ['id' => $banner->banner_id]) }}')">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-3 text-center text-gray-500">Banner tidak ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-4 flex justify-center space-x-1">
                @if ($banners->onFirstPage() === false)
                    <a href="{{ $banners->previousPageUrl() }}"
                        class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">&laquo;</a>
                @endif

                @foreach ($banners->getUrlRange(1, $banners->lastPage()) as $page => $url)
                    <a href="{{ $url }}"
                        class="px-3 py-1 rounded 
                {{ $banners->currentPage() === $page ? 'bg-[#560024] text-white' : 'bg-gray-200 hover:bg-gray-300' }}">
                        {{ $page }}
                    </a>
                @endforeach

                @if ($banners->hasMorePages())
                    <a href="{{ $banners->nextPageUrl() }}"
                        class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">&raquo;</a>
                @endif
            </div>
        </div>

        {{-- Modal --}}
        <div id="BannerModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div id="modalContent" class="mx-2 w-full max-w-lg">
                {{-- Konten ajax akan dimuat di sini --}}
            </div>
        </div>
    </div>
    @endsection

    @push('scripts')
        <script>
            // --- Buka modal ---
            function openBannerModal(url) {
                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.text())
                    .then(html => {
                        document.getElementById('modalContent').innerHTML = html;
                        document.getElementById('BannerModal').classList.remove('hidden');

                        // Pasang listener form edit setelah modal dimuat
                        const form = document.getElementById('editBannerForm');
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
                        const createForm = document.getElementById('createBannerForm');
                        if (createForm) {
                            createForm.addEventListener('submit', handleCreateSubmit);
                        }
                    })
                    .catch(err => console.error(err));
            }

            // --- Tutup modal ---
            function closeModal() {
                document.getElementById('BannerModal').classList.add('hidden');
                document.getElementById('modalContent').innerHTML = '';
            }

            // --- Tambahkan atribut data-metode-id pada row tabel ---
            document.querySelectorAll('tbody tr').forEach(tr => {
                const bannerId = tr.querySelector('td')?.innerText;
                if (bannerId) tr.setAttribute('data-banner-id', bannerId.trim());
            });
        </script>
    @endpush
