@extends('admin.layouts.app')

@section('content')
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Daftar Produk</h1>
            <a href="{{ url('/produk/create') }}"
                class="bg-gray-200 text-gray-800 hover:bg-gray-300 px-4 py-2 rounded shadow-sm">
                Tambah
            </a>
        </div>

        <div class="grid grid-cols-3 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @foreach ($produk as $p)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-200">
                    <a href="{{ url('/produk/' . $p->produk_id . '/show') }}">
                        <img src="{{ asset('storage/foto_produk/' . $p->fotoUtama->foto_produk) }}"
                            alt="{{ $p->nama_produk }}" class="w-full h-48 object-cover">
                    </a>
                    <div class="p-4">
                        <h2 class="text-lg font-semibold truncate">{{ $p->nama_produk }}</h2>
                        <p class="text-gray-500 text-sm mt-1">
                            {{ $p->kategori->nama_kategori ?? '-' }} | {{ $p->bahan->nama_bahan ?? '-' }}
                        </p>
                        <div class="flex justify-between items-center mt-4 space-x-2 text-sm">
                            <a href="{{ url('/produk/' . $p->produk_id . '/edit') }}"
                                class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition duration-200">
                                Edit
                            </a>

                            <!-- Trigger button for modal -->
                            <button onclick="document.getElementById('modal-{{ $p->produk_id }}').classList.remove('hidden')"
                                class="px-4 py-2 bg-black text-white rounded hover:bg-red-700 transition duration-200">
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modal -->
                <div id="modal-{{ $p->produk_id }}" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                    <div class="bg-white rounded-lg p-6 w-96 shadow-lg">
                        <h2 class="text-lg font-semibold mb-4">Konfirmasi Hapus</h2>
                        <p class="mb-6">Apakah Anda yakin ingin menghapus produk <strong>{{ $p->nama_produk }}</strong>?</p>
                        <div class="flex justify-end space-x-2">
                            <button onclick="document.getElementById('modal-{{ $p->produk_id }}').classList.add('hidden')"
                                class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition">
                                Batal
                            </button>
                            <form method="POST" action="{{ url('/produk/' . $p->produk_id . '/delete') }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">
                                    Ya, Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
