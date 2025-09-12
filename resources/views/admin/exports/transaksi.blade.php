<table>
    <thead>
        <tr>
            <th>Kode Invoice</th>
            <th>Nama Customer</th>
            <th>Email</th>
            <th>No Telp</th>
            <th>Status Transaksi</th>
            <th>Status Pembayaran</th>
            <th>Metode Pembayaran</th>
            <th>Total Belanja</th>
            <th>Jumlah Item</th>
            <th>Alamat Pengiriman</th>
            <th>Kurir</th>
            <th>Tanggal Pesanan</th>
            <th>Tanggal Pembayaran</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transaksi as $item)
            @php
                $totalBelanja = 0;
                foreach($item->detail as $d) {
                    $totalBelanja += ($d->produk->harga ?? 0) * $d->jumlah;
                }
            @endphp
            <tr>
                <td>{{ $item->kode_invoice ?? '-' }}</td>
                <td>{{ $item->nama_customer ?? '-' }}</td>
                <td>{{ $item->email ?? '-' }}</td>
                <td>{{ $item->no_telp ?? '-' }}</td>
                <td>{{ ucfirst($item->status_transaksi ?? '-') }}</td>
                <td>{{ $item->pembayaran->status_pembayaran ?? '-' }}</td>
                <td>{{ $item->pembayaran->metode->nama_pembayaran ?? '-' }}</td>
                <td>{{ 'Rp ' . number_format($totalBelanja, 0, ',', '.') }}</td>
                <td>{{ $item->detail->count() }}</td>
                <td>{{ $item->alamat_pengiriman ?? '-' }}</td>
                <td>{{ $item->kurir ?? '-' }}</td>
                <td>{{ $item->created_at ? $item->created_at->format('d-m-Y H:i') : '-' }}</td>
                <td>{{ $item->pembayaran->updated_at ? $item->pembayaran->updated_at->format('d-m-Y H:i') : '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
