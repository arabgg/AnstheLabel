<div class="bg-white rounded-lg shadow-lg w-full py-8 px-14 relative flex flex-col">
    <h2 class="text-xl font-semibold mb-4">{{ $banner->nama_banner }}</h2>

    <div class="space-y-3 text-sm flex-1">        
        @if($banner->foto_banner)
            @if($banner->is_video)
                <video class="w-full h-60 object-cover rounded" controls>
                    <source src="{{ asset('storage/banner/' . $banner->foto_banner) }}" type="video/mp4">
                </video>
            @else
                <img src="{{ asset('storage/banner/' . $banner->foto_banner) }}" 
                     class="w-full h-60 object-cover rounded" alt="{{ $banner->nama_banner }}">
            @endif
        @else
            <span class="text-gray-400">No Media</span>
        @endif

        <table class="w-full text-left border-collapse">
            <tbody class="text-sm">
                {{-- Deskripsi --}}
                <tr class="border-b border-transparent">
                    <td class="py-2 font-medium">Deskripsi</td>
                    <td class="py-2 px-2">:</td>
                    <td class="py-2">{{ $banner->deskripsi ?? '-' }}</td>
                </tr>

                {{-- Tanggal dibuat --}}
                <tr class="border-b border-transparent">
                    <td class="py-2 font-medium">Dibuat pada</td>
                    <td class="py-2 px-2">:</td>
                    <td class="py-2">{{ $banner->created_at->format('d M Y / H : i') }}</td>
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
