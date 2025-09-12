<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-bold mb-4">Tambah Metode Pembayaran</h2>
    <form action="{{ route('metode_pembayaran.store') }}" method="POST"
        id="createMetodeForm" enctype="multipart/form-data" class="text-sm">
        @csrf

        {{-- Metode --}}
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Metode</label>
            <select name="metode_id"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#560024]">
                <option value="" disabled selected>Pilih Metode</option>
                <option value="1" {{ old('metode_id') == 1 ? 'selected' : '' }}>Transfer Bank</option>
                <option value="2" {{ old('metode_id') == 2 ? 'selected' : '' }}>E-Wallet</option>
            </select>
        </div>

        {{-- Nama Pembayaran --}}
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Jenis Pembayaran</label>
            <input type="text" name="nama_pembayaran" value="{{ old('nama_pembayaran') }}"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#560024]">
        </div>

        {{-- Kode Bayar --}}
        <div class="mb-4" id="kode_bayar_text">
            <label class="block text-sm font-medium mb-1">Nomor Rekening / E-Wallet</label>
            <input type="text" name="kode_bayar" value="{{ old('kode_bayar') }}"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#560024]">
        </div>

        {{-- Atas Nama --}}
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Atas Nama</label>
            <input type="text" name="atas_nama" value="{{ old('atas_nama') }}"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#560024]">
        </div>

        {{-- Status Pembayaran --}}
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Status</label>
            <div class="flex items-center gap-4">
                <label class="flex items-center gap-2">
                    <input type="radio" name="status_pembayaran" value="1"
                        {{ old('status_pembayaran', '1') == '1' ? 'checked' : '' }}
                        class="rounded border-gray-300 text-[#560024] focus:ring-[#560024]">
                    Aktif
                </label>
                <label class="flex items-center gap-2">
                    <input type="radio" name="status_pembayaran" value="0"
                        {{ old('status_pembayaran') == '0' ? 'checked' : '' }}
                        class="rounded border-gray-300 text-[#560024] focus:ring-[#560024]">
                    Non-Aktif
                </label>
            </div>
        </div>

        {{-- Icon --}}
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Icon</label>
            <input type="file" name="icon"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#560024]">
        </div>

        {{-- Tombol --}}
        <div class="flex justify-end gap-2">
            <button type="button" onclick="closeModal()"
                class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
            <button type="submit" class="px-4 py-2 bg-[#560024] text-white rounded hover:bg-[#700030]">Simpan</button>
        </div>
    </form>
</div>
