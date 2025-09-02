<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-bold mb-4">Edit warna</h2>
    <form action="{{ route('warna.update', $warna->warna_id) }}" method="POST" id="editWarnaForm">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Nama Warna</label>
            <input type="text" name="nama_warna" value="{{ $warna->nama_warna }}" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#560024]">
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Kode Warna</label>
            <div class="flex items-center gap-2">
                <!-- Color Dot -->
                <div id="colorDot" class="w-5 h-5 rounded-full border" style="background-color: {{ $warna->kode_hex }}"></div>
                <!-- Input Field -->
                <input type="text" name="kode_hex"
                    value="{{ $warna->kode_hex }}"
                    class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#560024]"
                    placeholder="#FF0000">
            </div>
        </div>
        <div class="flex justify-end gap-2">
            <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
            <button type="submit" class="px-4 py-2 bg-[#560024] text-white rounded hover:bg-[#700030]">Simpan</button>
        </div>
    </form>
</div>