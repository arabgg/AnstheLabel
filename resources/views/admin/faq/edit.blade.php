<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-bold mb-4">Edit FAQ</h2>

    <form action="{{ route('faq.update', $faq->faq_id) }}" method="POST" id="editFaqForm">
        @csrf
        @method('PUT')

        {{-- Pertanyaan --}}
        <div class="mb-4">
            <label for="pertanyaan" class="block text-sm font-medium mb-1">Pertanyaan</label>
            <input 
                type="text" 
                id="pertanyaan"
                name="pertanyaan" 
                value="{{ old('pertanyaan', $faq->pertanyaan) }}" 
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#560024]"
                placeholder="Masukkan pertanyaan">

            @error('pertanyaan')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Jawaban --}}
        <div class="mb-4">
            <label for="jawaban" class="block text-sm font-medium mb-1">Jawaban</label>
            <textarea 
                id="jawaban"
                name="jawaban" 
                rows="4"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#560024]"
                placeholder="Masukkan jawaban">{{ old('jawaban', $faq->jawaban) }}</textarea>

            @error('jawaban')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tombol --}}
        <div class="flex justify-end gap-2">
            <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
            <button type="submit" class="px-4 py-2 bg-[#560024] text-white rounded hover:bg-[#700030]">Simpan</button>
        </div>
    </form>
</div>
