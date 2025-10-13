<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $order['kode_invoice'] }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&family=Playfair+Display:wght@600&display=swap');
        
        @page {
            size: A4;
            margin: 15mm 20mm;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body { 
            font-family: 'Montserrat', sans-serif; 
            font-size: 10px; 
            color: #4a2b33; 
            background-color: #fdf9f9;
            line-height: 1.3;
        }
        
        .mail-header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            border-bottom: 2px solid #560024;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        
        .mail-header-kode { 
            font-size: 10px; 
            color: #560024; 
            line-height: 1.4;
        }
        
        .mail-header-kode p {
            margin: 2px 0;
        }
        
        .mail-header img { 
            height: 35px; 
            width: auto; 
        }
        
        h1 { 
            text-align: center; 
            font-family: 'Playfair Display', serif; 
            color: #6b2e33; 
            font-size: 32px; 
            letter-spacing: 2px; 
            margin-bottom: 15px;
            margin-top: 5px;
        }
        
        .mail-info { 
            display: flex; 
            justify-content: space-between; 
            margin-bottom: 20px;
            gap: 30px;
        }
        
        .mail-section { 
            width: 48%; 
            font-size: 10px; 
            color: #6b2e33; 
            line-height: 1.5;
        }
        
        .mail-section b { 
            font-family: 'Playfair Display', serif; 
            font-size: 11px; 
            font-weight: bold; 
            color: #6b2e33; 
            display: block; 
            margin-bottom: 5px;
        }
        
        table { 
            width: 100%; 
            text-align: center; 
            border-collapse: collapse; 
            margin-bottom: 20px;
        }
        
        th { 
            font-size: 10px; 
            color: #6b2e33; 
            background: #f0cfd3; 
            padding: 6px 4px; 
            border: 1px solid #f0cfd3;
            font-weight: 600;
        }
        
        td { 
            padding: 5px 4px; 
            font-size: 10px;
            border-bottom: 1px solid #f5e5e7;
        }
        
        .divider { 
            border-top: 1.5px solid #d5b5b7; 
            margin: 15px 0;
        }
        
        .total { 
            text-align: right; 
            font-size: 13px; 
            font-weight: 600; 
            color: #6b2e33; 
            margin-bottom: 20px;
        }
        
        .status { 
            font-size: 10px;
            margin-top: 15px;
        }
        
        .status b { 
            font-size: 11px;
            display: block;
            margin-bottom: 3px;
        }
        
        .mail-info, table, .total, .status {
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
    <div class="mail-header">
        <div class="mail-header-kode">
            <p><strong>{{ $order['kode_invoice'] }}</strong></p>
            <p>Date: {{ now()->format('d-m-Y') }}</p>
        </div>
        <img src="{{ asset('storage/page/ansthelabel.png') }}" alt="Ansthelabel Logo">
    </div>
    
    <h1>INVOICE</h1>

    <div class="mail-info">
        <div class="mail-section">
            <b>BILL TO:</b>
            {{ $order['nama'] }}<br>
            {{ $order['alamat'] }}<br>
            {{ $order['email'] }}
        </div>
        <div class="mail-section">
            <b>PAYABLE TO:</b>
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
    
    <p class="total">TOTAL: Rp{{ number_format($order['total'], 0, ',', '.') }}</p>

    <div class="status">
        <p><b>Status Pesanan</b></p>
        <p>Menunggu Pembayaran</p>
    </div>
</body>
</html>