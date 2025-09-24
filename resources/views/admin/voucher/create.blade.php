<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-bold mb-4">Tambah Voucher</h2>
    <form action="{{ route('voucher.store') }}" method="POST" id="createVoucherForm">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Kiri --}}
            <div>
                {{-- Kode Voucher --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Kode Voucher <span class="text-red-600">*</span></label>
                    <input type="text" name="kode_voucher" value="{{ old('kode_voucher') }}"
                        placeholder="Masukkan kode voucher" required
                        class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#560024]">
                    @error('kode_voucher')
                        <p class="text-red-600 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Deskripsi --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Deskripsi <span class="text-red-600">*</span></label>
                    <textarea name="deskripsi" placeholder="Masukkan deskripsi voucher" required
                        class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#560024]">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <p class="text-red-600 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tipe Diskon --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Tipe Diskon <span
                            class="text-red-600">*</span></label>
                    <select name="tipe_diskon" required
                        class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#560024]">
                        <option value="" disabled selected>Pilih tipe diskon</option>
                        <option value="persen" {{ old('tipe_diskon') == 'persen' ? 'selected' : '' }}>Persen (%)
                        </option>
                        <option value="nominal" {{ old('tipe_diskon') == 'nominal' ? 'selected' : '' }}>Nominal Tetap
                        </option>
                    </select>
                    @error('tipe_diskon')
                        <p class="text-red-600 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nilai Diskon --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Nilai Diskon <span
                            class="text-red-600">*</span></label>
                    <input type="number" step="0.01" name="nilai_diskon" value="{{ old('nilai_diskon') }}"
                        placeholder="10% / 2000" required
                        class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#560024]">
                    @error('nilai_diskon')
                        <p class="text-red-600 text-sm">{{ $message }}</p>
                    @enderror
                </div>
                {{-- Minimal Transaksi --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Minimal Transaksi <span
                            class="text-red-600">*</span></label>
                    <input type="number" step="0.01" name="min_transaksi" value="{{ old('min_transaksi') }}"
                        placeholder="200000" required
                        class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#560024]">
                </div>
            </div>

            {{-- Kanan --}}
            <div>
                {{-- Batas Pemakaian --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Batas Pemakaian (Kosongkan jika unlimited)</label>
                    <input type="number" name="usage_limit" value="{{ old('usage_limit') }}"
                        class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#560024]">
                </div>

                {{-- Tanggal Mulai --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Tanggal Mulai <span
                            class="text-red-600">*</span></label>
                    <input type="datetime-local" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required
                        class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#560024]">
                </div>

                {{-- Tanggal Berakhir --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Tanggal Berakhir <span
                            class="text-red-600">*</span></label>
                    <input type="datetime-local" name="tanggal_berakhir" value="{{ old('tanggal_berakhir') }}" required
                        class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#560024]">
                </div>

                {{-- Status --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Status <span class="text-red-600">*</span></label>
                    <select name="status_voucher" required
                        class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#560024]">
                        <option value="" disabled selected>Pilih status</option>
                        <option value="1" {{ old('status_voucher') == 1 ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ old('status_voucher') == 0 ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Tombol --}}
        <div class="flex justify-end gap-2 mt-4">
            <button type="button" onclick="closeModal()"
                class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
            <button type="submit" class="px-4 py-2 bg-[#560024] text-white rounded hover:bg-[#700030]">Simpan</button>
        </div>
    </form>
</div>
