@extends('home.layouts.app')

@section('content')
<div class="invoice-container">
    <h2 class="invoice-title">Cek Transaksi</h2>

    @if(session('error'))
        <div class="invoice-error">{{ session('error') }}</div>
    @endif

    <form action="{{ route('invoice.cek') }}" method="POST" class="invoice-form">
        @csrf
        <label for="kode_invoice" class="invoice-label">Masukkan Kode Invoice:</label>
        <input type="text" name="kode_invoice" id="kode_invoice" class="invoice-input" required>
        <button type="submit" class="invoice-btn">Cek</button>
    </form>
</div>
@endsection
