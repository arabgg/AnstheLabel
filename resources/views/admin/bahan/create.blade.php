<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-bold mb-4">Tambah Bahan</h2>
    <form action="{{ route('bahan.store') }}" method="POST" id="createBahanForm">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Nama Bahan</label>
            <input type="text" name="nama_bahan" placeholder="Masukkan nama bahan" 
                   class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#560024]">
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Deskripsi</label>
            <textarea name="deskripsi" placeholder="Masukkan deskripsi bahan" 
                      class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#560024]"></textarea>
        </div>
        <div class="flex justify-end gap-2">
            <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
            <button type="submit" class="px-4 py-2 bg-[#560024] text-white rounded hover:bg-[#700030]">Simpan</button>
        </div>
    </form>
</div>
