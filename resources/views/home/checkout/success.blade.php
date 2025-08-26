@extends('home.layouts.app')

@section('content')
<div class="success-container">
    <h2 class="success-title">Pembayaran Berhasil Diproses</h2>

    <div class="success-box">
        <p><strong>Metode Pembayaran:</strong> {{ $detail->pembayaran->metode->nama_metode }}</p>

        <div class="invoice-box">
            <p>
                <strong>Kode Invoice:</strong> 
                <button class="copy-btn" onclick="copyToClipboard('invoiceCode')">
                    <i class="fa-regular fa-copy"></i>
                </button>
                <span id="invoiceCode">{{ $detail->transaksi->kode_invoice }}</span>
            </p>
        </div>

        <div class="payment-code">
            <p>
                <strong>Kode Bayar:</strong> 
                <button class="copy-btn" onclick="copyToClipboard('paymentCode')">
                    <i class="fa-regular fa-copy"></i>
                </button>
                <span id="paymentCode">{{ $detail->pembayaran->metode->kode_bayar }}</span>
            </p>
        </div>

        <p class="success-message">
            Silakan lanjutkan pembayaran sesuai metode yang dipilih.<br>
            Setelah pembayaran selesai, lakukan pengecekan transaksi di menu <strong>Transaction</strong>.
        </p>
    </div>

    <div class="success-btn-wrapper">
        <a href="{{ route('home') }}" class="success-btn">Kembali ke Beranda</a>
    </div>
</div>


<script>
    function copyToClipboard(elementId) {
        var text = document.getElementById(elementId).innerText;
        navigator.clipboard.writeText(text).then(() => {
            Swal.fire({
                toast: true,               // jadi mode notifikasi kecil
                position: 'top-end',       // pojok kanan atas
                icon: 'success',
                title: 'Berhasil disalin!',
                text: text,                // tampilkan kode yang dicopy
                showConfirmButton: false,
                timer: 2000,               // hilang otomatis 2 detik
                timerProgressBar: true
            });
        });
    }
</script>
@endsection
