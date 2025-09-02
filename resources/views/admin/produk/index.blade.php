@extends('admin.layouts.app')

@section('content')
<div class="p-8 bg-white rounded-lg shadow">
    <div class="flex justify-between items-start mb-7 border-b border-gray-300 pb-4">
        <h1 class="text-2xl font-bold pl-4 pt-4">Manage Produk</h1>
    </div>

    <div class="flex justify-end mb-7 mt-12">
        <form method="GET" action="{{ route('produk.index') }}" class="mr-3 flex items-center border rounded-lg px-3 py-2 w-1/3">
            <input type="text" name="search" placeholder="Search Produk" value="{{ request('search') }}" class="w-full outline-none placeholder:text-sm">
            <button type="submit" class="ml-2">
                <i class="fas fa-search"></i>
            </button>
        </form>
        
        <a href="{{ url('/produk/create') }}"
        class="px-7 py-2 bg-[#560024] text-white font-semibold rounded-lg hover:bg-gray-700 flex items-center justify-center text-sm">
            Tambah
        </a>
    </div>
    
    {{-- Produk Grid --}}
    <div class="grid gap-5 grid-cols-[repeat(auto-fill,minmax(220px,1fr))]">
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
    </div>

    {{-- Pagination --}}
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
</div>
@endsection

@push('scripts')
    <script>
        function toggleMenu(id) {
            const menu = document.getElementById('menu-' + id);
            const rect = menu.getBoundingClientRect();
            const spaceBelow = window.innerHeight - rect.bottom;

            if (spaceBelow < 150) {
                menu.style.top = 'auto';
                menu.style.bottom = '100%';
            } else {
                menu.style.top = '100%';
                menu.style.bottom = 'auto';
            }

            menu.classList.toggle('hidden');
        }

        document.addEventListener('click', function(e) {
            document.querySelectorAll('[id^="menu-"]').forEach(menu => {
                const toggleBtn = document.querySelector(
                    `[onclick="toggleMenu('${menu.id.replace('menu-', '')}')"]`);
                if (!menu.contains(e.target) && !toggleBtn.contains(e.target)) {
                    menu.classList.add('hidden');
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const kategoriFilter = document.getElementById('kategoriFilter');
            const paginateFilter = document.getElementById('paginateFilter');
            const sortFilter = document.getElementById('sortFilter');
            const form = document.getElementById('filterForm');

            kategoriFilter.addEventListener('change', function() {
                form.submit();
            });

            paginateFilter.addEventListener('change', function() {
                form.submit();
            });

            sortFilter.addEventListener('change', function() {
                form.submit();
            });
        });
    </script>
@endpush
