<div class="bg-white rounded-lg shadow-lg w-full py-8 px-14 relative flex flex-col">
    <h2 class="text-xl font-semibold mb-4">Detail Voucher</h2>
    <div class="space-y-3 text-sm flex-1">        
        <table class="w-full text-left border-collapse">
            <tbody class="text-sm">
                <tr class="border-b border-transparent">
                    <td class="py-2 font-medium">ID</td>
                    <td class="py-2 px-2">:</td>
                    <td class="py-2">{{ $voucher->voucher_id ?? '-' }}</td>
                </tr>
                <tr class="border-b border-transparent">
                    <td class="py-2 font-medium">Kode Voucher</td>
                    <td class="py-2 px-2">:</td>
                    <td class="py-2">{{ $voucher->kode_voucher ?? '-' }}</td>
                </tr>
                <tr class="border-b border-transparent">
                    <td class="py-2 font-medium">Deskripsi</td>
                    <td class="py-2 px-2">:</td>
                    <td class="py-2">{{ $voucher->deskripsi ?? '-' }}</td>
                </tr>
                <tr class="border-b border-transparent">
                    <td class="py-2 font-medium">Tipe Diskon</td>
                    <td class="py-2 px-2">:</td>
                    <td class="py-2">{{ ucfirst($voucher->tipe_diskon) ?? '-' }}</td>
                </tr>
                <tr class="border-b border-transparent">
                    <td class="py-2 font-medium">Nilai Diskon</td>
                    <td class="py-2 px-2">:</td>
                    <td class="py-2">
                        @if($voucher->tipe_diskon == 'persen')
                            {{ $voucher->nilai_diskon }}%
                        @else
                            Rp {{ number_format($voucher->nilai_diskon, 0, ',', '.') }}
                        @endif
                    </td>
                </tr>
                <tr class="border-b border-transparent">
                    <td class="py-2 font-medium">Minimal Transaksi</td>
                    <td class="py-2 px-2">:</td>
                    <td class="py-2">Rp {{ number_format($voucher->min_transaksi, 0, ',', '.') }}</td>
                </tr>
                <tr class="border-b border-transparent">
                    <td class="py-2 font-medium">Batas Pemakaian</td>
                    <td class="py-2 px-2">:</td>
                    <td class="py-2">
                        {{ $voucher->usage_limit ?? 'Unlimited' }}
                        (Dipakai: {{ $voucher->used }})
                    </td>
                </tr>
                <tr class="border-b border-transparent">
                    <td class="py-2 font-medium">Periode</td>
                    <td class="py-2 px-2">:</td>
                    <td class="py-2">
                        {{ $voucher->tanggal_mulai ? $voucher->tanggal_mulai->format('d M Y H:i') : '-' }}
                        s/d
                        {{ $voucher->tanggal_berakhir ? $voucher->tanggal_berakhir->format('d M Y H:i') : '-' }}
                    </td>
                </tr>
                <tr class="border-b border-transparent">
                    <td class="py-2 font-medium">Status</td>
                    <td class="py-2 px-2">:</td>
                    <td class="py-2">
                        @if($voucher->status_voucher)
                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded">Aktif</span>
                        @else
                            <span class="px-2 py-1 bg-red-100 text-red-700 rounded">Nonaktif</span>
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Tombol Tutup --}}
    <div class="flex justify-center mt-7 text-white font-semibold">
        <button onclick="closeModal()" class="px-4 py-2 bg-red-600 rounded hover:bg-red-700">
            Tutup
        </button>
    </div>
</div>
