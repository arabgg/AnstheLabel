{{-- Modal Overlay --}}
<div class="fixed inset-0 bg-black/50 flex items-center justify-center p-6 z-50">

    {{-- Modal Box --}}
    <div
        class="bg-white rounded-lg shadow-lg w-full max-w-5xl py-8 px-14 relative
               max-h-[90vh] overflow-y-auto">

        {{-- Judul --}}
        <h2 class="text-xl font-semibold mb-6">Detail Pesanan</h2>

        {{-- ===== INFORMASI TRANSAKSI + BUKTI ===== --}}
        <div class="flex gap-6">

            {{-- KIRI : DETAIL TRANSAKSI --}}
            <div class="flex-1">
                <table class="w-full text-left border-collapse">
                    <tbody class="text-sm">
                        <tr>
                            <td class="py-2 font-medium">Kode Invoice</td>
                            <td class="py-2 px-2">:</td>
                            <td class="py-2">{{ $transaksi->kode_invoice ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="py-2 font-medium">Nama Customer</td>
                            <td class="py-2 px-2">:</td>
                            <td class="py-2">{{ $transaksi->nama_customer ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="py-2 font-medium">No Telp</td>
                            <td class="py-2 px-2">:</td>
                            <td class="py-2">{{ $transaksi->no_telp ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="py-2 font-medium">Email</td>
                            <td class="py-2 px-2">:</td>
                            <td class="py-2">{{ $transaksi->email ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="py-2 font-medium">Alamat</td>
                            <td class="py-2 px-2">:</td>
                            <td class="py-2">{{ $transaksi->alamat ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="py-2 font-medium">Status Transaksi</td>
                            <td class="py-2 px-2">:</td>
                            <td class="py-2">
                                <span
                                    class="px-2 py-1 rounded-lg
                                    {{ $transaksi->status_transaksi == 'selesai'
                                        ? 'bg-green-100 text-green-700'
                                        : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ ucfirst($transaksi->status_transaksi) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="py-2 font-medium">Metode Pembayaran</td>
                            <td class="py-2 px-2">:</td>
                            <td class="py-2">
                                {{ $transaksi->pembayaran->metode->nama_pembayaran ?? '-' }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- KANAN : BUKTI PEMBAYARAN --}}
            <div class="w-64 shrink-0 border rounded-lg p-4 bg-gray-50 flex flex-col items-center">
                <p class="text-sm font-medium mb-3">Bukti Pembayaran</p>

                @if ($transaksi->pembayaran && $transaksi->pembayaran->bukti_pembayaran)
                    <a href="{{ asset('storage/bukti/' . $transaksi->pembayaran->bukti_pembayaran) }}"
                       target="_blank">
                        <img
                            src="{{ asset('storage/bukti/' . $transaksi->pembayaran->bukti_pembayaran) }}"
                            alt="Bukti Pembayaran"
                            class="w-full rounded shadow hover:scale-105 transition">
                    </a>
                    <span class="text-xs text-gray-500 mt-2">Klik gambar untuk memperbesar</span>
                @else
                    <div
                        class="w-full h-48 flex items-center justify-center
                               border-2 border-dashed text-gray-400 rounded">
                        Tidak ada bukti
                    </div>
                @endif
            </div>

        </div>

        {{-- ===== PRODUK DIPESAN ===== --}}
        <div class="mt-8">
            <h3 class="text-xl font-semibold mb-4">Produk Dipesan</h3>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[600px] text-left border-collapse text-sm">
                    <thead>
                        <tr class="border-b font-medium">
                            <th class="py-2 px-3">Produk</th>
                            <th class="py-2 px-3">Ukuran</th>
                            <th class="py-2 px-3">Warna</th>
                            <th class="py-2 px-3">Jumlah</th>
                            <th class="py-2 px-3">Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaksi->detail as $detail)
                            <tr class="border-b border-transparent">
                                <td class="py-2 px-3">{{ $detail->produk->nama_produk ?? '-' }}</td>
                                <td class="py-2 px-3">{{ $detail->ukuran->nama_ukuran ?? '-' }}</td>
                                <td class="py-2 px-3">{{ $detail->warna->nama_warna ?? '-' }}</td>
                                <td class="py-2 px-3">{{ $detail->jumlah }}</td>
                                <td class="py-2 px-3">
                                    Rp {{ number_format($detail->pembayaran->total_harga, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach

                        {{-- TOTAL --}}
                        <tr class="border-t font-semibold">
                            <td colspan="4" class="py-2 px-3 text-right">Total Harga</td>
                            <td class="py-2 px-3">
                                Rp {{ number_format($total, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tombol Tutup di bawah --}}
        <div class="flex justify-center mt-7 text-white font-semibold">
            <button onclick="closeModal()" class="px-4 py-2 bg-red-600 rounded hover:bg-red-700">
                Tutup
            </button>
        </div>
    </div>
</div>
