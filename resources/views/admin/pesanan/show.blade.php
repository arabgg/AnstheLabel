{{-- Konten untuk modal show pesanan --}}
<div class="bg-white rounded-lg shadow-lg w-full max-w-5xl py-8 px-14 relative flex flex-col">
    <h2 class="text-xl font-semibold mb-4">Detail Pesanan</h2>

    {{-- Informasi Transaksi --}}
    <div class="space-y-3 text-sm flex-1">
        <table class="w-full text-left border-collapse">
            <tbody class="text-sm">
                <tr class="border-b border-transparent">
                    <td class="py-2 font-medium">Kode Invoice</td>
                    <td class="py-2 px-2">:</td>
                    <td class="py-2">{{ $transaksi->kode_invoice ?? '-' }}</td>
                </tr>
                <tr class="border-b border-transparent">
                    <td class="py-2 font-medium">Customer</td>
                    <td class="py-2 px-2">:</td>
                    <td class="py-2">{{ $transaksi->nama_customer ?? '-' }}</td>
                </tr>
                <tr class="border-b border-transparent">
                    <td class="py-2 font-medium">No Telp</td>
                    <td class="py-2 px-2">:</td>
                    <td class="py-2">{{ $transaksi->no_telp ?? '-' }}</td>
                </tr>
                <tr class="border-b border-transparent">
                    <td class="py-2 font-medium">Email</td>
                    <td class="py-2 px-2">:</td>
                    <td class="py-2">{{ $transaksi->email ?? '-' }}</td>
                </tr>
                <tr class="border-b border-transparent">
                    <td class="py-2 font-medium">Alamat</td>
                    <td class="py-2 px-2">:</td>
                    <td class="py-2">{{ $transaksi->alamat ?? '-' }}</td>
                </tr>
                <tr class="border-b border-transparent">
                    <td class="py-2 font-medium">Status Transaksi</td>
                    <td class="py-2 px-2">:</td>
                    <td class="py-2">
                        <span
                            class="px-2 py-1 rounded-lg 
                            {{ $transaksi->status_transaksi == 'selesai' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                            {{ ucfirst($transaksi->status_transaksi) }}
                        </span>
                    </td>
                </tr>
                <tr class="border-b border-transparent">
                    <td class="py-2 font-medium">Metode Pembayaran</td>
                    <td class="py-2 px-2">:</td>
                    <td class="py-2">{{ $transaksi->pembayaran->metode->nama_pembayaran ?? '-' }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Produk Dipesan --}}
    <div class="mt-6">
        <h2 class="text-xl font-semibold mb-4">Produk Dipesan</h2>
        <div class="space-y-3 text-sm overflow-x-auto">
            <table class="w-full min-w-[600px] text-left border-collapse">
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
                    @php
                        $total = 0;
                    @endphp

                    @foreach ($transaksi->detail as $detail)
                        @php
                            $subtotal = ($detail->produk->harga ?? 0) * $detail->jumlah;
                            $total += $subtotal;
                        @endphp
                        <tr class="border-b border-transparent">
                            <td class="py-2 px-3">{{ $detail->produk->nama_produk ?? '-' }}</td>
                            <td class="py-2 px-3">{{ $detail->ukuran->nama_ukuran ?? '-' }}</td>
                            <td class="py-2 px-3">{{ $detail->warna->nama_warna ?? '-' }}</td>
                            <td class="py-2 px-3">{{ $detail->jumlah }}</td>
                            <td class="py-2 px-3">
                                Rp {{ number_format($subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach

                    {{-- Total Harga --}}
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
