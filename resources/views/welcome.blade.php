<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $order['kode_invoice'] }}</title>
    <style>
        body { font-family: DejaVuSans; font-size: 13px; color: #4a2b33; background-color: #fdf9f9; /* background-image: url('storage/page/ansthelabel-icon.png'); background-repeat: no-repeat; background-position: center; background-size: contain; */ margin: 50px 80px; }
        .mail-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #560024; }
        .mail-header-kode { text-align: left; font-size: 14px; color: #560024; line-height: 0.8; margin: 20px 0; }
        .mail-header img { height: 50px; width: auto; }
        h1 { text-align: center; color: #6b2e33; font-size: 70px; letter-spacing: 3px; margin-bottom: 40px; }
        .mail-info { display: flex; justify-content: space-between; margin: 0 50px; margin-bottom: 50px; }
        .mail-section { width: 45%; font-size: 14px; color: #6b2e33; line-height: 1.6; }
        .mail-section b { font-size: 16px; font-weight: bold; color: #6b2e33; display: block; margin-bottom: 5px; }
        table { width: 100%; text-align: center; border-collapse: collapse; margin-bottom: 80px; }
        th { font-size: 14px; color: #6b2e33; background: #f0cfd3; padding: 10px; border: 1px solid #f0cfd3; }
        td { padding: 8px 0; font-size: 13px; }
        .divider { border-top: 1px solid #d5b5b7; margin: 20px 0; }
        .total { text-align: right; font-size: 16px; font-weight: 600; color: #6b2e33; margin-bottom: 30px; }
        .status b { font-size: 16px; }
        .status { font-size: 14px; }
    </style>
</head>
<body>
    <div class="mail-header">
        <div class="mail-header-kode">
            <p>{{ $order['kode_invoice'] }}</p>
            <p>Date: {{ now()->format('d-m-Y') }}</p>
        </div>
        <img src="{{ asset('storage/page/ansthelabel.png') }}" alt="Ansthelabel Logo">
    </div>
    <h1>INVOICE</h1>

    <div class="mail-info">
        <div class="mail-section">
            <b>BILL TO :</b>
            {{ $order['nama'] }}<br>
            {{ $order['alamat'] }}<br>
            {{ $order['email'] }}
        </div>
        <div class="mail-section" style="text-align:left;">
            <b>PAYABLE TO :</b>
            Ansthelabel<br>
            Qris<br>
            ansthelabel@fashion.com
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Warna</th>
                <th>Ukuran</th>
                <th>Qty</th>
                <th>Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                <tr>
                    <td>{{ $item['nama'] }}</td>
                    <td>{{ $item['warna_nama'] ?? '-' }}</td>
                    <td>{{ $item['ukuran_nama'] ?? '-' }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>Rp{{ number_format($item['harga'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="divider"></div>
    <p class="total">TOTAL : Rp{{ number_format($order['total'], 0, ',', '.') }}</p>

    <div class="status">
        <p><b>Status Pesanan</b></p>
        <p>Menunggu Pembayaran</p>
    </div>
</body>
</html>

