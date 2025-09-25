<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg w-full py-8 px-14 relative flex flex-col">
        <h2 class="text-xl font-semibold mb-4">Detail Ekspedisi</h2>

        <div class="space-y-3 text-sm flex-1">        
            <table class="w-full text-left border-collapse">
                <tbody class="text-sm">
                    <tr class="border-b border-transparent">
                        <td class="py-2 font-medium">ID</td>
                        <td class="py-2 px-2">:</td>
                        <td class="py-2">{{ $ekspedisi->ekspedisi_id ?? '-' }}</td>
                    </tr>
                    <tr class="border-b border-transparent">
                        <td class="py-2 font-medium">Nama Ekspedisi</td>
                        <td class="py-2 px-2">:</td>
                        <td class="py-2">{{ $ekspedisi->nama_ekspedisi ?? '-' }}</td>
                    </tr>
                    <tr class="border-b border-transparent">
                        <td class="py-2 font-medium">Status</td>
                        <td class="py-2 px-2">:</td>
                        <td class="py-2">
                            @if($ekspedisi->status_ekspedisi)
                                <span class="px-2 py-1 bg-green-200 text-green-800 rounded">Aktif</span>
                            @else
                                <span class="px-2 py-1 bg-red-200 text-red-800 rounded">Nonaktif</span>
                            @endif
                        </td>
                    </tr>
                    <tr class="border-b border-transparent">
                        <td class="py-2 font-medium">Icon</td>
                        <td class="py-2 px-2">:</td>
                        <td class="py-2">
                            @if($ekspedisi->icon)
                                <img src="{{ asset('storage/icons/' . $ekspedisi->icon) }}" alt="icon" class="w-20 h-15 mt-1">
                            @else
                                <span class="text-gray-500">Tidak ada</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Tombol Tutup --}}
        <div class="flex justify-center mt-7 text-white font-semibold">
            <a href="{{ route('ekspedisi.index') }}" class="px-4 py-2 bg-red-600 rounded hover:bg-red-700">
                Tutup
            </a>
        </div>
    </div>
</div>