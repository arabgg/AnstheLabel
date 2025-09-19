<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-bold mb-4">Tambah Warna</h2>
    <form action="{{ route('warna.store') }}" method="POST" id="createWarnaForm">
        @csrf
        {{-- Input warna --}}
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Pilih Warna <span class="text-gray-400" title="Klik kotak untuk memilih warna">🎨</span></label>
            <div class="flex gap-2 items-center">
                {{-- Color Picker dengan tooltip --}}
                <input type="color" name="kode_hex" value="#000000" class="w-12 h-10 p-0 border rounded cursor-pointer" id="colorPicker" title="Klik untuk memilih warna">
            </div>
            <small class="text-gray-500">Gunakan format HEX (contoh: #FF0000)</small>
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-semibold">Nama Warna</label>
            <input type="text" name="nama_warna" class="w-full border px-3 py-2 rounded"
                value="{{ old('nama_warna') }}" required>
        </div>

        <div class="flex justify-end gap-2">
            <button type="button" onclick="closeModal()"
                class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
            <button type="submit" class="px-4 py-2 bg-[#560024] text-white rounded hover:bg-[#700030]">Simpan</button>
        </div>
    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const colorPicker = document.getElementById('colorPicker');
    const hexInput = document.getElementById('hexInput');

    // Ketika user pilih dari color picker
    colorPicker.addEventListener("input", () => {
        hexInput.value = colorPicker.value.toUpperCase();
    });
});
</script>
