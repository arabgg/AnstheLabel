<div class="bg-white rounded-lg shadow p-6 max-w-3xl mx-auto">
    <h2 class="text-xl font-bold mb-4">Edit Metode Pembayaran</h2>

    <form action="{{ route('metode_pembayaran.update', $mp->metode_pembayaran_id) }}" method="POST" enctype="multipart/form-data" class="space-y-4" id="editMetodeForm">
        @csrf
        @method('PUT')

        {{-- Dropdown Metode --}}
        <div>
            <label for="metode_id" class="block text-sm font-medium mb-1">Metode</label>
            <select name="metode_id" id="metode_id" required
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#560024]">
                <option value="">-- Pilih Metode --</option>
                @foreach ($metodes as $metode)
                    <option value="{{ $metode->metode_id }}"
                        {{ old('metode_id', $mp->metode_id) == $metode->metode_id ? 'selected' : '' }}>
                        {{ $metode->nama_metode }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Nama Pembayaran --}}
        <div>
            <label for="nama_pembayaran" class="block text-sm font-medium mb-1">Nama Pembayaran</label>
            <input type="text" name="nama_pembayaran" id="nama_pembayaran"
                value="{{ old('nama_pembayaran', $mp->nama_pembayaran) }}"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#560024]">
            @error('nama_pembayaran')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Icon --}}
        <div>
            <label for="icon" class="block text-sm font-medium mb-1">Icon</label>
            @if ($mp->icon)
                <div class="mb-2">
                    <img src="{{ asset('storage/icons/' . $mp->icon) }}" alt="Icon" class="h-12">
                </div>
            @endif
            <input type="file" name="icon" id="icon"
                class="w-full text-sm text-gray-500 file:mr-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-indigo-700 hover:file:bg-indigo-100">
            @error('icon')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Kode Bayar --}}
        <div>
            <label for="kode_bayar" class="block text-sm font-medium mb-1">Kode Bayar</label>
            <input type="text" name="kode_bayar" id="kode_bayar"
                value="{{ old('kode_bayar', $mp->kode_bayar) }}"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#560024]">
            @error('kode_bayar')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Status --}}
        <div>
            <label for="status_pembayaran" class="block text-sm font-medium mb-1">Status</label>
            <select name="status_pembayaran" id="status_pembayaran"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#560024]">
                <option value="1" {{ old('status_pembayaran', $mp->status_pembayaran) == 1 ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ old('status_pembayaran', $mp->status_pembayaran) == 0 ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>

        {{-- Action Buttons --}}
        <div class="flex justify-end gap-2 pt-4">
            <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
            <button type="submit" class="px-4 py-2 bg-[#560024] text-white rounded hover:bg-[#700030]">Simpan</button>
        </div>
    </form>
</div>

