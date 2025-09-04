<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-bold mb-4">Tambah Kategori</h2>
    <form action="{{ route('kategori.store') }}" method="POST" id="createKategoriForm">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Nama Kategori</label>
            <input type="text" name="nama_kategori" placeholder="Masukkan nama kategori" 
                   class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#560024]">
        </div>
        <div class="flex justify-end gap-2">
            <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
            <button type="submit" class="px-4 py-2 bg-[#560024] text-white rounded hover:bg-[#700030]">Simpan</button>
        </div>
    </form>
</div>
