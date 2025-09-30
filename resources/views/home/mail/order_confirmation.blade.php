<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Confirmation</title>
</head>
<body>
    <h2>Terima Kasih, {{ $order['nama'] }}!</h2>
    <p>
        Kami telah menerima pesanan Anda dengan kode invoice <b>{{ $order['kode_invoice'] }}</b>.  
        Rincian transaksi sudah kami lampirkan dalam bentuk PDF pada email ini.
    </p>
    <p>
        <b>Total Pembayaran:</b> Rp {{ number_format($order['total'], 0, ',', '.') }}
    </p>
    <p>
        Cek status pesanan Anda di halaman berikut:  
        <a href="{{ route('transaksi.show', ['kode_invoice' => $order['kode_invoice']]) }}" target="_blank">
            Cek Pesanan
        </a>
    </p>
    <p>
        Salam hangat,<br>
        <b>Ansthelabel</b>
    </p>
</body>
</html>
