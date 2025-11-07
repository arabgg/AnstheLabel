<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-bold mb-4">Edit Metode Pembayaran</h2>
    <form action="{{ route('banner.update', $banner->banner_id) }}" method="POST" id="editBannerForm"
        enctype="multipart/form-data" class="text-sm">
        @csrf
        @method('PUT')
        <div class="mb-5">
            @if ($banner->foto_banner)
                @if (pathinfo($banner->foto_banner, PATHINFO_EXTENSION) === 'mp4')
                    <video class="w-full h-44 object-cover mb-2 rounded-lg" controls>
                        <source src="{{ asset('storage/banner/' . $banner->foto_banner) }}" type="video/mp4">
                    </video>
                @else
                    <img src="{{ asset('storage/banner/' . $banner->foto_banner) }}" alt="Banner"
                        class="w-full h-44 rounded-lg object-cover mb-2">
                @endif
            @endif
            <input type="file" name="foto_banner" class="w-full border p-2 rounded-lg file:rounded-lg">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Deskripsi</label>
            <input type="text" name="deskripsi" value="{{ $banner->deskripsi }}"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#560024]">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Status</label>
            @if (!in_array($banner->banner_id, [1, 5, 9, 13, 17, 21, 22, 23, 24]))
                <div class="flex items-center gap-4">
                    <label class="flex items-center gap-2">
                        <input type="radio" name="status" value="1" {{ $banner->status == 1 ? 'checked' : '' }}
                            class="rounded border-gray-300 text-[#560024] focus:ring-[#560024]">
                        Aktif
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="radio" name="status" value="0" {{ $banner->status == 0 ? 'checked' : '' }}
                            class="rounded border-gray-300 text-[#560024] focus:ring-[#560024]">
                        Non-Aktif
                    </label>
                </div>
            @else
                <input type="hidden" name="status" value="{{ $banner->status }}">
                <p class="text-gray-500 italic">Status tidak dapat diubah untuk banner ini.</p>
            @endif
        </div>

        {{-- Tombol --}}
        <div class="flex justify-end gap-2">
            <button type="button" onclick="closeModal()"
                class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
            <button type="submit" class="px-4 py-2 bg-[#560024] text-white rounded hover:bg-[#700030]">Simpan</button>
        </div>
    </form>
</div>
