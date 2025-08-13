@extends('admin.layouts.app')

@section('content')
    <div class="p-6 bg-white rounded-lg">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6 gap-4">

            {{-- Judul Halaman & Filter --}}
            <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full md:w-auto">
                <h1 class="text-2xl font-bold">Daftar Produk</h1>

                {{-- Filter Kategori --}}
                <select
                    class="border border-gray-500 rounded-lg px-3 py-2 text-sm w-full sm:w-auto focus:outline-none focus:ring-2 focus:ring-pink-300">
                    <option value="">Kategori</option>
                    <option value="atasan">Atasan</option>
                    <option value="bawahan">Bawahan</option>
                </select>

                {{-- Filter Ukuran --}}
                <select
                    class="border border-gray-500 rounded-lg px-3 py-2 text-sm w-full sm:w-auto focus:outline-none focus:ring-2 focus:ring-pink-300">
                    <option value="">Ukuran</option>
                    <option value="S">S</option>
                    <option value="M">M</option>
                    <option value="L">L</option>
                </select>

                {{-- Filter Warna --}}
                <select
                    class="border border-gray-500 rounded-lg px-3 py-2 text-sm w-full sm:w-auto focus:outline-none focus:ring-2 focus:ring-pink-300">
                    <option value="">Warna</option>
                    <option value="hitam">Hitam</option>
                    <option value="putih">Putih</option>
                    <option value="merah">Merah</option>
                </select>
            </div>

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

        <div class="grid gap-5 grid-cols-[repeat(auto-fit,minmax(220px,1fr))]">
            @foreach ($produk as $p)
                <div class="p-2 bg-[#F0F4F9] rounded-xl shadow-md overflow-hidden hover:bg-[#DFE3E7] transition duration-200">
                    <a href="{{ url('/produk/' . $p->produk_id . '/show') }}">
                        <div class="aspect-[1/1] rounded-xl overflow-hidden">
                            <img src="{{ asset('storage/foto_produk/' . $p->fotoUtama->foto_produk) }}"
                                alt="{{ $p->nama_produk }}" class="w-full h-full object-cover">
                        </div>
                    </a>
                    <div class="p-4">
                        <div>
                            <h2 class="text-lg font-semibold font-montserrat truncate">{{ $p->nama_produk }}</h2>
                        </div>
                        <div class="flex pl-4 pr-4 justify-between space-x-7 items-center mt-4">
                            <a href="{{ url('/produk/' . $p->produk_id . '/edit') }}"
                                class="px-4 py-2 bg-black text-base text-white border rounded-xl hover:bg-gray-700 transition duration-200">
                                Edit
                            </a>

                            <button
                                onclick="document.getElementById('modal-{{ $p->produk_id }}').classList.remove('hidden')"
                                class="px-4 py-2 bg-white text-base border border-gray-300 text-black rounded-xl hover:bg-red-500 transition duration-200">
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Modal --}}
                <div id="modal-{{ $p->produk_id }}"
                    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                    <div class="bg-white rounded-lg p-6 w-96 shadow-lg">
                        <h2 class="text-lg font-semibold mb-4">Konfirmasi Hapus</h2>
                        <p class="mb-6">Apakah Anda yakin ingin menghapus produk <strong>{{ $p->nama_produk }}</strong>?
                        </p>
                        <div class="flex justify-center space-x-10">
                            <form method="POST" action="{{ url('/produk/' . $p->produk_id . '/delete') }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="flex px-4 py-2 bg-red-500 text-white rounded hover:bg-red-800 transition">
                                    Ya, Hapus
                                </button>
                            </form>
                            <button onclick="document.getElementById('modal-{{ $p->produk_id }}').classList.add('hidden')"
                                class="flex px-4 py-2 bg-gray-300 text-gray-900 rounded hover:bg-gray-400 transition">
                                Batal
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
