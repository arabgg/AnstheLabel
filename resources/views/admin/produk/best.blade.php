@extends('admin.layouts.app')

@section('content')
<div class="p-2">
    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 text-red-600 rounded-xl">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    <form id="produk-form" action="{{ route('produk.postBest') }}" method="POST"
        class="bg-white p-6 rounded-xl shadow-md space-y-6">
        @csrf

        <div class="p-8 bg-white rounded-lg shadow">
            <div class="flex justify-between items-start mb-7 border-b border-gray-300 pb-4">
                <h1 class="text-2xl font-bold pl-4 pt-4">Edit Best Produk</h1>
            </div>

            <div class="grid gap-5 grid-cols-[repeat(auto-fill,minmax(220px,1fr))]">
                @foreach ($best as $p)
                    <div class="p-2 bg-slate-200 rounded-xl shadow-md hover:bg-[#DFE3E7] transition duration-200 cursor-pointer group
                        {{ $p->is_best ? 'ring-4 ring-[#560024]' : '' }}"
                        onclick="toggleProduct({{ $p->produk_id }}, this)">
                        
                        <div class="flex justify-between items-center mb-2">
                            <h2 class="text-small font-montserrat truncate">{{ $p->nama_produk }}</h2>
                        </div>

                        <div class="aspect-[4/5] rounded-xl overflow-hidden relative">
                            <img src="{{ asset('storage/foto_produk/' . $p->fotoUtama->foto_produk) }}"
                                alt="{{ $p->nama_produk }}"
                                class="w-full h-full object-cover transition-transform duration-200 group-hover:scale-105">

                            <div
                                class="absolute inset-0 bg-black/40 {{ $p->is_best ? 'flex' : 'hidden' }} items-center justify-center text-white text-4xl rounded-xl selected-overlay">
                                <i class="fa-solid fa-check-circle"></i>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="pt-6 flex flex-col items-center gap-2">
            <p id="count-info" class="text-sm text-gray-600">
                Pilih <span id="selected-count">{{ $best->where('is_best', 1)->count() }}</span>/5 produk terbaik
            </p>

            <div class="flex justify-between items-center gap-4 w-full">
                <a href="{{ url('/produk') }}"
                    class="flex-[1] text-center px-4 py-4 bg-gray-200 text-gray-800 rounded-xl font-medium hover:bg-gray-300 transition duration-300 shadow-sm">
                    Batal
                </a>
                <button id="submit-btn" type="submit"
                    class="flex-[2] text-center py-4 bg-[#560024] text-white rounded-xl font-medium transition duration-300 shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                    Simpan
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    const selectedProducts = new Set(
        @json($best->where('is_best', 1)->pluck('produk_id')->toArray())
    );

    const submitBtn = document.getElementById('submit-btn');
    const countInfo = document.getElementById('selected-count');

    function toggleProduct(id, element) {
        const overlay = element.querySelector('.selected-overlay');

        if (selectedProducts.has(id)) {
            selectedProducts.delete(id);
            overlay.classList.add('hidden');
            element.classList.remove('ring-4', 'ring-[#560024]');
        } else {
            if (selectedProducts.size >= 5) {
                alert('Kamu hanya bisa memilih tepat 5 produk.');
                return;
            }
            selectedProducts.add(id);
            overlay.classList.remove('hidden');
            element.classList.add('ring-4', 'ring-[#560024]');
        }

        updateUI();
    }

    function updateUI() {
        // Update jumlah tampilan
        countInfo.textContent = selectedProducts.size;

        // Aktif/nonaktif tombol
        submitBtn.disabled = selectedProducts.size !== 5;

        // Hapus input lama
        document.querySelectorAll('input[name="produk_id[]"]').forEach(e => e.remove());

        // Tambahkan input baru
        selectedProducts.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'produk_id[]';
            input.value = id;
            document.getElementById('produk-form').appendChild(input);
        });
    }

    // Inisialisasi awal
    updateUI();
</script>
@endpush
