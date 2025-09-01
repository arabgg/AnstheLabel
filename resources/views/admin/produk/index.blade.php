@extends('admin.layouts.app')

@section('content')
<div class="p-2 bg-white rounded-xl">
    <div class="flex bg-[#560024] py-4 justify-center mb-4 rounded-xl">
        <h1 class="text-2xl font-bold font-montserrat text-white">Daftar Produk</h1>
    </div>

    <div class="flex flex-col md:flex-row md:justify-center mb-4 gap-7">
        {{-- Filter & Search --}}
        <form id="filterForm" method="GET" action="{{ url('/produk') }}"
            class="flex flex-col sm:flex-row sm:items-center gap-3 w-full md:w-auto bg-white p-3 rounded-lg shadow">

            {{-- Filter Kategori --}}
            <select name="kategori" id="kategoriFilter"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full sm:w-auto focus:outline-none focus:ring-2 focus:ring-pink-300">
                <option value="">All</option>
                @foreach ($kategoriList as $kategori)
                    <option value="{{ $kategori->kategori_id }}"
                        {{ request('kategori') == $kategori->kategori_id ? 'selected' : '' }}>
                        {{ $kategori->nama_kategori }}
                    </option>
                @endforeach
            </select>

            {{-- Filter Paginate --}}
            <select name="paginate" id="paginateFilter"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full sm:w-auto focus:outline-none focus:ring-2 focus:ring-pink-300">
                @foreach ([5, 15, 25, 50, 100] as $limit)
                    <option value="{{ $limit }}" {{ request('paginate', 15) == $limit ? 'selected' : '' }}>
                        Tampilkan {{ $limit }}
                    </option>
                @endforeach
            </select>

            {{-- Search Bar --}}
            <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden w-full sm:w-auto">
                <input type="text" name="search" placeholder="Cari nama produk..." value="{{ request('search') }}"
                    class="w-full px-4 py-2 text-gray-700 focus:outline-none">
                <button type="submit" class="px-4 py-2 bg-black text-white hover:bg-gray-700 transition duration-200">
                    Cari
                </button>
            </div>
        </form>

        {{-- Tombol Tambah --}}
        <a href="{{ url('/produk/create') }}"
            class="inline-flex items-center justify-center text-base bg-black text-white px-6 py-5 rounded-xl shadow-md hover:bg-gray-700 transition w-full md:w-auto">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Baru
        </a>
    </div>

    {{-- Produk Grid --}}
    <div class="grid gap-5 grid-cols-[repeat(auto-fill,minmax(220px,1fr))]">
        @foreach ($produk as $p)
            <div
                class="p-2 bg-[#F0F4F9] rounded-xl shadow-md overflow-hidden hover:bg-[#DFE3E7] transition duration-200">
                <a href="{{ url('/produk/' . $p->produk_id . '/show') }}">
                    <div class="aspect-[4/5] rounded-xl overflow-hidden">
                        <img src="{{ asset('storage/foto_produk/' . $p->fotoUtama->foto_produk) }}"
                            alt="{{ $p->nama_produk }}" class="w-full h-full object-cover">
                    </div>
                </a>
                <div class="p-2">
                    <div>
                        <h2 class="text-lg font-semibold font-montserrat truncate">{{ $p->nama_produk }}</h2>
                    </div>
                    <div class="flex P-4 justify-between items-center mt-4">
                        <button
                            onclick="document.getElementById('modal-{{ $p->produk_id }}').classList.remove('hidden')"
                            class="px-4 py-2 bg-white text-base border border-gray-300 text-black rounded-xl hover:bg-red-500 transition duration-200">
                            Hapus
                        </button>
                        <a href="{{ url('/produk/' . $p->produk_id . '/edit') }}"
                            class="px-4 py-2 bg-black text-base text-white border rounded-xl hover:bg-gray-700 transition duration-200">
                            Edit
                        </a>
                    </div>
                </div>
            </div>

            {{-- Modal --}}
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

    {{-- Pagination Links --}}
    <div class="mt-8">
        {{ $produk->appends(request()->except('page'))->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const kategoriFilter = document.getElementById('kategoriFilter');
        const paginateFilter = document.getElementById('paginateFilter');
        const form = document.getElementById('filterForm');

        kategoriFilter.addEventListener('change', function() {
            form.submit();
        });

        paginateFilter.addEventListener('change', function() {
            form.submit();
        });
    });
</script>
@endpush