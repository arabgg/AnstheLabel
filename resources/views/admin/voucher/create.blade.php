<head>
    <meta charset="UTF-8">
    <title>Tambah Voucher</title>
    <script src="https://unpkg.com/alpinejs" defer></script>
</head>

<body class="bg-gray-100 p-6">

    <div x-data="{ step: 1 }" class="bg-white rounded-lg shadow p-6 max-w-3xl mx-auto">
        <h2 class="text-xl font-bold mb-4">Tambah Voucher</h2>
        <form action="{{ route('voucher.store') }}" method="POST" id="createVoucherForm">
            @csrf

            <!-- Step 1 -->
            <div x-show="step === 1" x-transition>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Kode Voucher <span
                            class="text-red-600">*</span></label>
                    <input type="text" name="kode_voucher" value="{{ old('kode_voucher') }}" required
                        placeholder="Masukkan kode voucher"
                        class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#560024]">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Deskripsi <span class="text-red-600">*</span></label>
                    <textarea name="deskripsi" required placeholder="Masukkan deskripsi voucher"
                        class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#560024]">{{ old('deskripsi') }}</textarea>
                </div>

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
                </div>
            </div>

            <!-- Step 2 -->
            <div x-show="step === 2" x-transition>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Nilai Diskon <span
                            class="text-red-600">*</span></label>
                    <input type="number" step="0.01" name="nilai_diskon" value="{{ old('nilai_diskon') }}" required
                        placeholder="10% / 2000"
                        class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#560024]">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Minimal Transaksi <span
                            class="text-red-600">*</span></label>
                    <input type="number" step="0.01" name="min_transaksi" value="{{ old('min_transaksi') }}"
                        required placeholder="200000"
                        class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#560024]">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Batas Pemakaian (Kosongkan jika unlimited)</label>
                    <input type="number" name="usage_limit" value="{{ old('usage_limit') }}"
                        class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#560024]">
                </div>
            </div>

            <!-- Step 3 -->
            <div x-show="step === 3" x-transition>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Tanggal Mulai <span
                            class="text-red-600">*</span></label>
                    <input type="datetime-local" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required
                        class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#560024]">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Tanggal Berakhir <span
                            class="text-red-600">*</span></label>
                    <input type="datetime-local" name="tanggal_berakhir" value="{{ old('tanggal_berakhir') }}" required
                        class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#560024]">
                </div>

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

            <!-- Tombol Navigasi -->
            <div class="flex justify-between mt-6">
                <button type="button" onclick="closeModal()"
                    class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
                <div class="flex gap-2">
                    <button type="button" x-show="step > 1" @click="step--"
                        class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Sebelumnya</button>
                    <button type="button" x-show="step < 3" @click="step++"
                        class="px-4 py-2 bg-[#560024] text-white rounded hover:bg-[#700030]">Selanjutnya</button>
                    <button type="submit" x-show="step === 3"
                        class="px-4 py-2 bg-[#560024] text-white rounded hover:bg-[#700030]">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</body>
