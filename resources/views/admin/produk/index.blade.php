@extends('admin.layouts.app')

@section('content')
<div class="p-8 bg-white rounded-lg shadow">
    <div class="flex justify-between items-start mb-7 border-b border-gray-300 pb-4">
        <h1 class="text-2xl font-bold pl-4 pt-4">Kelola Produk</h1>
    </div>

    <div class="flex justify-between items-center mb-5">
        {{-- Search & Filter Form --}}
        <form id="filterForm" method="GET" action="{{ route('produk.index') }}" class="flex items-center w-full">
            <div class="flex-grow mr-3 flex items-center border rounded-lg px-3 py-2">
                <input type="text" name="search" placeholder="Cari Produk" value="{{ request('search') }}" class="w-full outline-none placeholder:text-sm">
                <button type="submit" class="ml-2">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            
            {{-- Dropdown Kategori --}}
            <div class="mr-3">
                <select id="kategoriFilter" name="kategori" class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-gray-400 transition-colors">
                    <option value="">Semua Kategori</option>
                    @foreach ($kategoriList as $kategori)
                        <option value="{{ $kategori->kategori_id }}" {{ request('kategori') == $kategori->kategori_id ? 'selected' : '' }}>
                            {{ $kategori->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Dropdown Bahan --}}
            <div class="mr-3">
                <select id="bahanFilter" name="bahan" class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-gray-400 transition-colors">
                    <option value="">Semua Bahan</option>
                    @foreach ($bahanList as $bahan)
                        <option value="{{ $bahan->bahan_id }}" {{ request('bahan') == $bahan->bahan_id ? 'selected' : '' }}>
                            {{ $bahan->nama_bahan }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Dropdown Sort --}}
            <div class="mr-3">
                <select id="sortFilter" name="sort" class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-gray-400 transition-colors">
                    <option value="">Urutkan</option>
                    <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                    <option value="terlama" {{ request('sort') == 'terlama' ? 'selected' : '' }}>Terlama</option>
                    <option value="terupdate" {{ request('sort') == 'terupdate' ? 'selected' : '' }}>Terupdate</option>
                    <option value="stok_terbanyak" {{ request('sort') == 'stok_terbanyak' ? 'selected' : '' }}>Stok Terbanyak</option>
                    <option value="stok_tersedikit" {{ request('sort') == 'stok_tersedikit' ? 'selected' : '' }}>Stok Tersedikit</option>
                    <option value="harga_termahal" {{ request('sort') == 'harga_termahal' ? 'selected' : '' }}>Harga Termahal</option>
                    <option value="harga_termurah" {{ request('sort') == 'harga_termurah' ? 'selected' : '' }}>Harga Termurah</option>
                </select>
            </div>
            
            {{-- Tombol Tambah Produk --}}
            <a href="{{ url('/produk/create') }}"
            class="px-7 py-2 bg-[#560024] text-white font-semibold rounded-lg hover:bg-gray-700 flex items-center justify-center text-sm ml-auto">
                Tambah
            </a>
        </form>
    </div>
    
    {{-- Produk Grid --}}
    <div class="grid gap-5 grid-cols-[repeat(auto-fill,minmax(220px,1fr))]">
        @if ($produk->isEmpty())
            <div class="col-span-full text-center py-10">
                <p class="text-gray-500 text-lg">Produk tidak ditemukan.</p>
            </div>
        @else
            @foreach ($produk as $p)
                <div class="p-2 bg-slate-200 rounded-xl shadow-md overflow-visible hover:bg-[#DFE3E7] transition duration-200">
                    {{-- Nama Produk + Dropdown --}}
                    <div class="flex justify-between items-center mb-2">
                        <h2 class="text-small font-montserrat truncate">{{ $p->nama_produk }}</h2>

                        <div x-data="{ open: false }" class="relative inline-block text-left">
                            <button @click="open = !open" class="rounded-full px-2 hover:bg-gray-300 transition">
                                <i class="fa-solid fa-ellipsis-vertical text-gray-700 text-sm"></i>
                            </button>

                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-44 bg-white border border-gray-200 rounded-lg shadow-lg z-50 transition-all duration-200 ease-out">
                                <a href="{{ url('/produk/' . $p->produk_id . '/edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-black hover:bg-gray-100 transition">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                    <span>Edit</span>
                                </a>
                                <button type="button" onclick="document.getElementById('modal-{{ $p->produk_id }}').classList.remove('hidden');" class="flex items-center gap-2 w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 hover:text-red-700 transition">
                                    <i class="fa-regular fa-trash-can text-red-500"></i>
                                    <span>Hapus</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Gambar Produk --}}
                    <a href="{{ url('/produk/' . $p->produk_id . '/show') }}">
                        <div class="aspect-[4/5] rounded-xl overflow-hidden">
                            <img src="{{ asset('storage/foto_produk/' . $p->fotoUtama->foto_produk) }}" alt="{{ $p->nama_produk }}" class="w-full h-full object-cover">
                        </div>
                    </a>
                </div>

                {{-- Modal Konfirmasi Hapus --}}
                <div id="modal-{{ $p->produk_id }}"
                    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden"
                    onclick="if(event.target === this) this.classList.add('hidden')">
                    <div class="bg-white rounded-lg p-6 w-96 shadow-lg">
                        <h2 class="text-lg font-semibold mb-4">Konfirmasi Hapus</h2>
                        <p class="mb-6">
                            Apakah Anda yakin ingin menghapus produk
                            <strong>{{ $p->nama_produk }}</strong>?
                        </p>
                        <div class="flex justify-center space-x-10">
                            <form method="POST" action="{{ route('produk.destroy', $p->produk_id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="flex px-4 py-2 bg-red-500 text-white rounded hover:bg-red-800 transition">
                                    Ya, Hapus
                                </button>
                            </form>
                            <button type="button"
                                onclick="document.getElementById('modal-{{ $p->produk_id }}').classList.add('hidden')"
                                class="flex px-4 py-2 bg-gray-300 text-gray-900 rounded hover:bg-gray-400 transition">
                                Batal
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    {{-- Pagination --}}
    @if (!$produk->isEmpty())
        <div class="mt-4 flex justify-center space-x-1">
            @if ($produk->onFirstPage() === false)
                <a href="{{ $produk->previousPageUrl() }}" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">&laquo;</a>
            @endif

            @foreach ($produk->getUrlRange(1, $produk->lastPage()) as $page => $url)
                <a href="{{ $url }}" class="px-3 py-1 rounded 
                    {{ $produk->currentPage() === $page ? 'bg-[#560024] text-white' : 'bg-gray-200 hover:bg-gray-300' }}">
                    {{ $page }}
                </a>
            @endforeach

            @if ($produk->hasMorePages())
                <a href="{{ $produk->nextPageUrl() }}" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">&raquo;</a>
            @endif
        </div>
    @endif
</div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const kategoriFilter = document.getElementById('kategoriFilter');
            const bahanFilter = document.getElementById('bahanFilter');
            const paginateFilter = document.getElementById('paginateFilter');
            const sortFilter = document.getElementById('sortFilter');
            const form = document.getElementById('filterForm');

            kategoriFilter.addEventListener('change', function() {
                form.submit();
            });

            bahanFilter.addEventListener('change', function() {
                form.submit();
            });

            sortFilter.addEventListener('change', function() {
                form.submit();
            });

            paginateFilter.addEventListener('change', function() {
                form.submit();
            });
        });
    </script>
@endpush