<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg w-full py-8 px-14 relative flex flex-col">
        <h2 class="text-xl font-semibold mb-4">Detail Metode Pembayaran</h2>

        <div class="space-y-3 text-sm flex-1">        
            <table class="w-full text-left border-collapse">
                <tbody class="text-sm">
                    <tr class="border-b border-transparent">
                        <td class="py-2 font-medium">ID</td>
                        <td class="py-2 px-2">:</td>
                        <td class="py-2">{{ $metode->metode_pembayaran_id ?? '-' }}</td>
                    </tr>
                    <tr class="border-b border-transparent">
                        <td class="py-2 font-medium">Nama</td>
                        <td class="py-2 px-2">:</td>
                        <td class="py-2">{{ $metode->nama_pembayaran ?? '-' }}</td>
                    </tr>
                    <tr class="border-b border-transparent">
                        <td class="py-2 font-medium">Kode Bayar</td>
                        <td class="py-2 px-2">:</td>
                        <td class="py-2">{{ $metode->kode_bayar ?? '-' }}</td>
                    </tr>
                    <tr class="border-b border-transparent">
                        <td class="py-2 font-medium">Atas Nama</td>
                        <td class="py-2 px-2">:</td>
                        <td class="py-2">{{ $metode->atas_nama ?? '-' }}</td>
                    </tr>
                    <tr class="border-b border-transparent">
                        <td class="py-2 font-medium">Status</td>
                        <td class="py-2 px-2">:</td>
                        <td class="py-2">
                            @if($metode->status_pembayaran)
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
                            @if($metode->icon)
                                <img src="{{ asset('storage/icons/' . $metode->icon) }}" alt="icon" class="w-20 h-15 mt-1">
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
            <a href="{{ route('metode_pembayaran.index') }}" class="px-4 py-2 bg-red-600 rounded hover:bg-red-700">
                Tutup
            </a>
        </div>
    </div>
</div>