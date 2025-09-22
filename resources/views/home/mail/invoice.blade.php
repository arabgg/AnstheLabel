<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $order['kode_invoice'] }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Invoice: {{ $order['kode_invoice'] }}</h2>
    <p><b>Nama:</b> {{ $order['nama'] }}</p>
    <p><b>Email:</b> {{ $order['email'] }}</p>
    <p><b>Alamat:</b> {{ $order['alamat'] }}</p>
    <p><b>Total:</b> Rp {{ number_format($order['total'], 0, ',', '.') }}</p>

    <h3>Detail Pesanan</h3>
    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th>Warna</th>
                <th>Ukuran</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                <tr>
                    <td>{{ $item['nama'] }}</td>
                    <td>{{ $item['warna_nama'] ?? '-' }}</td>
                    <td>{{ $item['ukuran_nama'] ?? '-' }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item['harga'] * $item['quantity'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
