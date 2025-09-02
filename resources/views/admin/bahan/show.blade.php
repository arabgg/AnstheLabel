<div class="bg-white rounded-lg shadow-lg w-full py-8 px-14 relative flex flex-col">
    <h2 class="text-xl font-semibold mb-4">Detail Bahan</h2>
    <div class="space-y-3 text-sm flex-1">        
        <table class="w-full text-left border-collapse">
            <tbody class="text-sm">
                <tr class="border-b border-transparent">
                    <td class="py-2 font-medium">ID</td>
                    <td class="py-2 px-2">:</td>
                    <td class="py-2">{{ $bahan->bahan_id ?? '-' }}</td>
                </tr>
                <tr class="border-b border-transparent">
                    <td class="py-2 font-medium">Nama bahan</td>
                    <td class="py-2 px-2">:</td>
                    <td class="py-2">{{ $bahan->nama_bahan ?? '-' }}</td>
                </tr>
                <tr class="border-b border-transparent">
                    <td class="py-2 font-medium">Deskripsi</td>
                    <td class="py-2 px-2">:</td>
                    <td class="py-2">{{ $bahan->deskripsi ?? '-' }}</td>
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