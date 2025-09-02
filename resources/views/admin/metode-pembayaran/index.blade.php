@extends('admin.layouts.app')

@section('content')
<div class="p-8 bg-white rounded-lg shadow">
    {{-- Judul --}}
    <div class="flex justify-between items-start mb-7 border-b border-gray-300 pb-4">
        <h1 class="text-2xl font-bold pl-4 pt-4">Manage Metode Pembayaran</h1>
    </div>

    {{-- Search --}}
    <div class="flex justify-end mb-5">
        <form method="GET" action="{{ route('metode-pembayaran.index') }}" class="flex items-center border rounded px-3 py-2 w-1/3">
            <input type="text" name="search" placeholder="Search Metode Pembayaran" value="{{ $searchQuery ?? '' }}" class="w-full outline-none placeholder:text-sm">
            <button type="submit" class="ml-2">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>

    {{-- Tabel item --}}
    <div class="overflow-x-auto rounded-lg">
        <table class="w-full table-auto border-collapse text-center">
            <thead class="bg-[#560024] text-white text-sm">
                <tr>
                    <th class="p-3">ID</th>
                    <th class="p-3">METODE</th>
                    <th class="p-3">PEMBAYARAN</th>
                    <th class="p-3">KODE BAYAR</th>
                    <th class="p-3">ICON</th>
                    <th class="p-3">DIBUAT</th>
                    <th class="p-3">UPDATE</th>
                    <th class="p-3">ACTION</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse ($metode as $item)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3">{{ $item->metode_pembayaran_id }}</td>
                        <td class="p-3">{{ $item->metode->nama_metode }}</td>
                        <td class="p-3">{{ $item->nama_pembayaran }}</td>
                        <td class="p-3">
                            <div class="flex justify-center">
                                @if($item->kode_bayar_type === 'image')
                                    <img src="{{ asset('storage/icons/' . $item->kode_bayar) }}" class="w-20 h-20 rounded">
                                @elseif($item->kode_bayar_type === 'text')
                                    <span class="text-gray-800 font-medium">{{ $item->kode_bayar }}</span>
                                @else
                                    <span class="text-gray-400">No Media</span>
                                @endif
                            </div>
                        </td>
                        <td class="p-3">
                            <img src="{{ asset('storage/icons/' . $item->icon) }}" class="w-12 rounded">
                        </td>
                        <td class="p-3">{{ $item->created_at->format('d M Y [ H : i ]') }}</td>
                        <td class="p-3">{{ $item->updated_at->format('d M Y [ H : i ]') }}</td>
                        <td class="p-3 mt-5 flex gap-2 justify-center items-center">
                            {{-- Tombol Detail --}}
                            <button 
                                class="flex items-center justify-center py-2 px-3 rounded-lg border border-gray-400 text-black hover:bg-blue-400 hover:border-blue-400"
                                onclick="openitemModal('{{ route('metode-pembayaran.show', ['id' => $item->metode_pembayaran_id]) }}')">
                                <i class="fa-solid fa-database"></i>
                            </button>

                            {{-- Tombol Edit --}}
                            <a href="{{ route('metode-pembayaran.edit', $item->metode_pembayaran_id) }}" 
                                class="flex items-center justify-center py-2 px-3 rounded-lg border border-gray-400 text-black 
                                hover:bg-yellow-300 hover:border-yellow-300">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </a>

                            {{-- Tombol Hapus --}}
                            <form action="{{ route('metode-pembayaran.destroy', $item->metode_pembayaran_id) }}" method="POST" 
                                onsubmit="return confirm('Hapus item ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="flex items-center justify-center py-2 px-3 rounded-lg border border-gray-400 text-black 
                                            hover:bg-red-500 hover:border-red-500">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
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
        @if ($metode->onFirstPage() === false)
            <a href="{{ $metode->previousPageUrl() }}" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">&laquo;</a>
        @endif

        @foreach ($metode->getUrlRange(1, $metode->lastPage()) as $page => $url)
            <a href="{{ $url }}" class="px-3 py-1 rounded 
                {{ $metode->currentPage() === $page ? 'bg-[#560024] text-white' : 'bg-gray-200 hover:bg-gray-300' }}">
                {{ $page }}
            </a>
        @endforeach

        @if ($metode->hasMorePages())
            <a href="{{ $metode->nextPageUrl() }}" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">&raquo;</a>
        @endif
    </div>
</div>

{{-- Modal --}}
<div id="itemModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div id="modalContent" class="mx-2 w-full max-w-lg">
        {{-- Konten show.blade.php akan dimuat di sini --}}
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openitemModal(url) {
        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.text())
        .then(html => {
            document.getElementById('modalContent').innerHTML = html;
            document.getElementById('itemModal').classList.remove('hidden');
        })
        .catch(err => console.error(err));
    }

    function closeModal() {
        document.getElementById('itemModal').classList.add('hidden');
        document.getElementById('modalContent').innerHTML = '';
    }
</script>
@endpush
