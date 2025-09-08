<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-bold mb-4">Edit Metode Pembayaran</h2>
    <form action="{{ route('banner.update', $banner->banner_id) }}" method="POST" id="editBannerForm" enctype="multipart/form-data" class="text-sm">
        @csrf
        @method('PUT')
        <div class="mb-5">
            @if($banner->foto_banner)
                @if(pathinfo($banner->foto_banner, PATHINFO_EXTENSION) === 'mp4')
                    <video class="w-full h-44 object-cover mb-2 rounded-lg" controls>
                        <source src="{{ asset('storage/banner/' . $banner->foto_banner) }}" type="video/mp4">
                    </video>
                @else
                    <img src="{{ asset('storage/banner/' . $banner->foto_banner) }}" alt="Banner" class="w-full h-44 rounded-lg object-cover mb-2">
                @endif
            @endif
            <input type="file" name="foto_banner" class="w-full border p-2 rounded-lg file:rounded-lg" required>
        </div>

        {{-- Tombol --}}
        <div class="flex justify-end gap-2">
            <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
            <button type="submit" class="px-4 py-2 bg-[#560024] text-white rounded hover:bg-[#700030]">Simpan</button>
        </div>
    </form>
</div>