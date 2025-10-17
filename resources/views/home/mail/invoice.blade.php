<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $order['kode_invoice'] }}</title>
    <style>
        @page {
            size: A4;
        }

        body {
            font-family: DejaVuSans, sans-serif;
            font-size: 12px;
            color: #4a2b33;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
        }

        .page {
            width: 90%;
            padding: 10mm;
            box-sizing: border-box;
        }

        /* === HEADER === */
        .mail-header {
            width: 100%;
            border-bottom: 2px solid #560024;
            margin-bottom: 20px;
        }

        .mail-header td {
            vertical-align: middle;
        }

        .mail-header-kode {
            font-size: 13px;
            color: #560024;
            line-height: 1.4;
        }

        .mail-header img {
            height: 45px;
            width: auto;
        }

        /* === TITLE === */
        h1 {
            text-align: center;
            color: #6b2e33;
            font-size: 45px;
            letter-spacing: 2px;
            margin: 25px 0 35px 0;
        }

        /* === BILL TO / PAYABLE TO === */
        .mail-info {
            width: 100%;
            margin-bottom: 40px;
        }

        .mail-info td {
            width: 50%;
            vertical-align: top;
            padding: 5px 10px;
            color: #6b2e33;
            font-size: 13px;
            line-height: 1.6;
        }

        .mail-info b {
            font-size: 15px;
            color: #6b2e33;
            display: block;
            margin-bottom: 5px;
        }

        /* === TABLE (ITEMS) === */
        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }

        table.items th {
            font-size: 13px;
            color: #6b2e33;
            background: #f0cfd3;
            padding: 8px;
            border: 1px solid #f0cfd3;
        }

        table.items td {
            font-size: 12px;
            padding: 8px;
            text-align: center;
            border: 1px solid #f0cfd3;
        }

        /* === DIVIDER & TOTAL === */
        .divider {
            border-top: 1px solid #d5b5b7;
            margin: 15px 0;
        }

        .total {
            text-align: right;
            font-size: 15px;
            font-weight: bold;
            color: #6b2e33;
            margin-top: 10px;
            margin-bottom: 30px;
        }

        /* === STATUS === */
        .status {
            font-size: 13px;
            color: #4a2b33;
        }

        .status b {
            display: block;
            font-size: 15px;
            margin-bottom: 5px;
        }

    </style>
</head>
<body>
    <div class="page">
        <!-- HEADER -->
        <table class="mail-header">
            <tr>
                <td align="left" class="mail-header-kode">
                    <p><strong>{{ $order['kode_invoice'] }}</strong></p>
                    <p>Date: {{ now()->format('d-m-Y') }}</p>
                </td>
                <td align="right">
                    <img src="{{ asset('storage/page/ansthelabel.png') }}" alt="Ansthelabel Logo">
                </td>
            </tr>
        </table>

        <!-- TITLE -->
        <h1>INVOICE</h1>

        <!-- INFO -->
        <table class="mail-info">
            <tr>
                <td>
                    <b>BILL TO :</b>
                    {{ $order['nama'] }}<br>
                    {{ $order['alamat'] }}<br>
                    {{ $order['email'] }}
                </td>
                <td>
                    <b>PAYABLE TO :</b>
                    Ansthelabel<br>
                </td>
            </tr>
        </table>

        <!-- ITEMS TABLE -->
        <table class="items">
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
                        <td>IDR {{ number_format($item['harga'], 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- TOTAL -->
        <div class="divider"></div>
        <p class="total">TOTAL : IDR {{ number_format($order['total'], 0, ',', '.') }}</p>

        <!-- STATUS -->
        <div class="status">
            <b>Status Pesanan</b>
            <p>Menunggu Pembayaran</p>
        </div>
    </div>
</body>
</html>
