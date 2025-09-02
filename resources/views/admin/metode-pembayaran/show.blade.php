<div class="bg-white rounded-lg shadow-lg w-full py-8 px-14 relative flex flex-col">
    <h2 class="text-xl font-semibold mb-4">Detail Metode Pembayaran</h2>
    <div class="space-y-3 text-sm flex-1">        
        <table class="w-full text-left border-collapse">
            <tbody class="text-sm">
                <tr class="border-b border-transparent">
                    <td class="py-2 font-medium">Metode</td>
                    <td class="py-2 px-2">:</td>
                    <td class="py-2">{{ $metode->metode->nama_metode ?? '-' }}</td>
                </tr>
                <tr class="border-b border-transparent">
                    <td class="py-2 font-medium">Jenis Pembayaran</td>
                    <td class="py-2 px-2">:</td>
                    <td class="py-2">{{ $metode->nama_pembayaran ?? '-' }}</td>
                </tr>
                <tr class="border-b border-transparent">
                    <td class="py-2 font-medium">Kode Bayar</td>
                    <td class="py-2 px-2">:</td>
                    <td class="py-2">
                        <div class="flex justify-left">
                            @if($metode->kode_bayar_type === 'image' && $metode->kode_bayar)
                                <img src="{{ asset('storage/icons/' . $metode->kode_bayar) }}" class="w-20 h-20 rounded">
                            @elseif($metode->kode_bayar_type === 'text' && $metode->kode_bayar)
                                <span class="text-gray-800 font-medium">{{ $metode->kode_bayar }}</span>
                            @else
                                <span class="text-gray-400">No Media</span>
                            @endif
                        </div>
                    </td>
                </tr>
                <tr class="border-b border-transparent">
                    <td class="py-2 font-medium">Icon</td>
                    <td class="py-2 px-2">:</td>
                    <td class="py-2">
                        <div class="flex justify-left">
                            @if($metode->icon)
                                <img src="{{ asset('storage/icons/' . $metode->icon) }}" class="w-12 rounded">
                            @else
                                <span class="text-gray-400">No Icon</span>
                            @endif
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Tombol Tutup di bawah kanan --}}
    <div class="flex justify-center mt-7 text-white font-semibold">
        <button onclick="closeModal()" class="px-4 py-2 bg-red-600 rounded hover:bg-red-700">
            Tutup
        </button>
    </div>
</div>
