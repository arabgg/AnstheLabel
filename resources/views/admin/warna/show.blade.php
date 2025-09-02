<div class="bg-white rounded-lg shadow-lg w-full py-8 px-14 relative flex flex-col">
    <h2 class="text-xl font-semibold mb-4">Detail Warna</h2>
    <div class="space-y-3 text-sm flex-1">        
        <table class="w-full text-left border-collapse">
            <tbody class="text-sm">
                <tr class="border-b border-transparent">
                    <td class="py-2 font-medium">ID</td>
                    <td class="py-2 px-2">:</td>
                    <td class="py-2">{{ $warna->warna_id ?? '-' }}</td>
                </tr>
                <tr class="border-b border-transparent">
                    <td class="py-2 font-medium">Nama warna</td>
                    <td class="py-2 px-2">:</td>
                    <td class="py-2">{{ $warna->nama_warna ?? '-' }}</td>
                </tr>
                <tr class="border-b border-transparent">
                    <td class="py-2 font-medium">Kode Warna</td>
                    <td class="py-2 px-2">:</td>
                    <td class="py-2">
                        <div class="flex items-center gap-2">
                            <div class="w-5 h-5 rounded-full border border-gray-700" style="background-color: {{ $warna->kode_hex }};"></div>
                            <span>{{ $warna->kode_hex }}</span>
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