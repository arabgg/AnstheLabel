<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-bold mb-4">Edit Ekspedisi</h2>
    <form action="{{ route('ekspedisi.update', $ekspedisi->ekspedisi_id) }}" method="POST"
        id="editEkspedisiForm" enctype="multipart/form-data" class="text-sm">
        @csrf
        @method('PUT')

        {{-- Nama Ekspedisi --}}
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Nama Ekspedisi</label>
            <input type="text" name="nama_ekspedisi" value="{{ $ekspedisi->nama_ekspedisi }}"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#560024]">
        </div>
        
         {{-- Icon --}}
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Icon</label>
            <input type="file" name="icon"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#560024]">

            {{-- simpan nama file lama --}}
            <input type="hidden" name="old_icon" value="{{ $ekspedisi->icon }}">

            @if ($ekspedisi->icon)
                <div class="mt-2">
                    <img src="{{ asset('storage/icons/' . $ekspedisi->icon) }}"
                        class="px-3 py-2 w-20 border border-gray-400 rounded-lg">
                </div>
            @endif
        </div>
        
        {{-- Status Ekspedisi --}}
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Status</label>
            <div class="flex items-center gap-4">
                <label class="flex items-center gap-2">
                    <input type="radio" name="status_ekspedisi" value="1"
                        {{ $ekspedisi->status_ekspedisi == 1 ? 'checked' : '' }}
                        class="rounded border-gray-300 text-[#560024] focus:ring-[#560024]">
                    Aktif
                </label>
                <label class="flex items-center gap-2">
                    <input type="radio" name="status_ekspedisi" value="0"
                        {{ $ekspedisi->status_ekspedisi == 0 ? 'checked' : '' }}
                        class="rounded border-gray-300 text-[#560024] focus:ring-[#560024]">
                    Non-Aktif
                </label>
            </div>
        </div>

        {{-- Tombol --}}
        <div class="flex justify-end gap-2">
            <button type="button" onclick="closeModal()"
                class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
            <button type="submit" class="px-4 py-2 bg-[#560024] text-white rounded hover:bg-[#700030]">Simpan</button>
        </div>
    </form>
</div>
