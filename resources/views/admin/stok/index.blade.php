@extends('admin.layouts.app')

@section('content')
    <div class="container bg-white rounded-lg min-h-screen">
        <div class="p-8 bg-white rounded-lg shadow">
            {{-- Judul --}}
            <div class="flex justify-between items-start mb-7 border-b border-gray-300 pb-4">
                <h1 class="text-2xl font-bold pl-4 pt-4">Kelola Stok Produk</h1>
            </div>

            {{-- Search & Sort --}}
            <div class="flex justify-between items-center mb-7 mt-6">
                <div class="flex items-center gap-3 w-2/3">
                    {{-- Form Search --}}
                    <form method="GET" action="{{ route('stok.index') }}"
                        class="flex items-center border rounded-lg px-3 py-2 w-1/2">
                        <input type="text" name="search" placeholder="Cari Nama Produk" value="{{ $searchQuery ?? '' }}"
                            class="w-full outline-none placeholder:text-sm">
                        <button type="submit" class="ml-2">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>

                    {{-- Dropdown Filter Status --}}
                    <form method="GET" action="{{ route('stok.index') }}">
                        <select id="statusFilter" name="status"
                            class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-gray-400 transition-colors"
                            onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="aman" {{ request('status') == 'aman' ? 'selected' : '' }}>Stok Aman</option>
                            <option value="mulai" {{ request('status') == 'mulai' ? 'selected' : '' }}>Mulai Restock
                            </option>
                            <option value="habis" {{ request('status') == 'habis' ? 'selected' : '' }}>Harus Restock
                            </option>
                        </select>
                    </form>

                    {{-- Dropdown Sort --}}
                    <form method="GET" action="{{ route('stok.index') }}">
                        <select id="sortFilter" name="sort"
                            class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-gray-400 transition-colors"
                            onchange="this.form.submit()">
                            <option value="">Urutkan Berdasarkan</option>
                            <option value="stok_asc" {{ request('sort') == 'stok_asc' ? 'selected' : '' }}>Stok Sedikit →
                                Banyak</option>
                            <option value="stok_desc" {{ request('sort') == 'stok_desc' ? 'selected' : '' }}>Stok Banyak →
                                Sedikit</option>
                        </select>
                    </form>

                </div>
            </div>

            {{-- Tabel item --}}
            <div class="overflow-x-auto rounded-lg">
                <table class="w-full table-auto border-collapse text-center">
                    <thead class="bg-[#560024] text-white text-sm">
                        <tr>
                            <th class="p-3">NO</th>
                            <th class="p-3">NAMA PRODUK</th>
                            <th class="p-3">STOK TERSEDIA</th>
                            <th class="p-3">STATUS STOK</th>
                            {{-- <th class="p-3">AKSI</th> --}}
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse ($produk as $item)
                            <tr class="border-b hover:bg-gray-50" data-kategori-id="{{ $item->produk_id }}">
                                <td class="p-3">{{ $produk->firstItem() + $loop->index }}</td>
                                <td class="p-3">{{ $item->nama_produk }}</td>
                                <td class="p-3">{{ $item->stok_produk }} item</td>
                                <td class="p-3">
                                    <div class="flex items-center justify-center gap-2">
                                        @if ($item->stok_produk > 5)
                                            <div class="p-2 rounded-lg bg-green-500 text-white font-semibold text-center">
                                                Stok Aman
                                            </div>
                                        @elseif ($item->stok_produk >= 4 && $item->stok_produk <= 5)
                                            <div class="p-2 rounded-lg bg-orange-400 text-white font-semibold text-center">
                                                Mulai Restock
                                            </div>
                                        @else
                                            <div class="p-2 rounded-lg bg-red-500 text-white font-semibold text-center">
                                                Harus Restock
                                            </div>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-3 text-center text-gray-500">Item tidak ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-4 flex justify-center space-x-1">
                @if ($produk->onFirstPage() === false)
                    <a href="{{ $produk->previousPageUrl() }}"
                        class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">&laquo;</a>
                @endif

                @foreach ($produk->getUrlRange(1, $produk->lastPage()) as $page => $url)
                    <a href="{{ $url }}"
                        class="px-3 py-1 rounded 
                            {{ $produk->currentPage() === $page ? 'bg-[#560024] text-white' : 'bg-gray-200 hover:bg-gray-300' }}">
                        {{ $page }}
                    </a>
                @endforeach

                @if ($produk->hasMorePages())
                    <a href="{{ $produk->nextPageUrl() }}"
                        class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">&raquo;</a>
                @endif
            </div>
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
@endpush
