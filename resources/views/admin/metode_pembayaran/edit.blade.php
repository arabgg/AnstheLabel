<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-bold mb-4">Edit Metode Pembayaran</h2>
    <form action="{{ route('metode_pembayaran.update', $metode->metode_pembayaran_id) }}" method="POST" id="editMetodeForm" enctype="multipart/form-data" class="text-sm">
        @csrf
        @method('PUT')

        {{-- Metode --}}
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Metode</label>
            <select name="metode_id" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#560024]">
                <option value="1" {{ $metode->metode_id == 1 ? 'selected' : '' }}>Transfer Bank</option>
                <option value="2" {{ $metode->metode_id == 2 ? 'selected' : '' }}>E-Wallet</option>
            </select>
        </div>

        {{-- Nama Pembayaran --}}
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Jenis Pembayaran</label>
            <input type="text" name="nama_pembayaran" value="{{ $metode->nama_pembayaran }}" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#560024]">
        </div>

        {{-- Kode Bayar --}}
        <div class="mb-4" id="kode_bayar_text" style="{{ $metode->kode_bayar_type === 'text' ? '' : 'display:none;' }}">
            <label class="block text-sm font-medium mb-1">Kode Bayar</label>
            <input type="text" name="kode_bayar" value="{{ $metode->kode_bayar_type === 'text' ? $metode->kode_bayar : '' }}" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#560024]">
        </div>

        {{-- Icon --}}
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Icon</label>
            <input type="file" name="icon" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#560024]">
            
            {{-- simpan nama file lama --}}
            <input type="hidden" name="old_icon" value="{{ $metode->icon }}">

            @if($metode->icon)
                <div class="mt-2">
                    <img src="{{ asset('storage/icons/' . $metode->icon) }}" 
                        class="px-3 py-2 w-20 border border-gray-400 rounded-lg">
                </div>
            @endif
        </div>

        {{-- Tombol --}}
        <div class="flex justify-end gap-2">
            <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
            <button type="submit" class="px-4 py-2 bg-[#560024] text-white rounded hover:bg-[#700030]">Simpan</button>
        </div>
    </form>
</div>