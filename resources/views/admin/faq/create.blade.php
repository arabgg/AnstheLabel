<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-bold mb-4">Tambah FAQ</h2>
    <form action="{{ route('faq.store') }}" method="POST" id="createFaqForm">
        @csrf

        {{-- Pertanyaan --}}
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Pertanyaan</label>
            <input type="text" name="pertanyaan" placeholder="Masukkan pertanyaan"
                   class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#560024]" required>
        </div>

        {{-- Jawaban --}}
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Jawaban</label>
            <textarea name="jawaban" rows="4" placeholder="Masukkan jawaban"
                      class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#560024]" required></textarea>
        </div>

        {{-- Tombol --}}
        <div class="flex justify-end gap-2">
            <button type="button" onclick="closeModal()" 
                    class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
            <button type="submit" 
                    class="px-4 py-2 bg-[#560024] text-white rounded hover:bg-[#700030]">Simpan</button>
        </div>
    </form>
</div>
