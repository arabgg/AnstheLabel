@extends('home.layouts.app')

@section('content')
<div class="container">
    <h2>Cek Transaksi</h2>

    @if(session('error'))
        <div style="color:red">{{ session('error') }}</div>
    @endif

    <form action="{{ route('invoice.cek') }}" method="POST">
        @csrf
        <label for="kode_invoice">Masukkan Kode Invoice:</label>
        <input type="text" name="kode_invoice" id="kode_invoice" required>
        <button type="submit">Cek</button>
    </form>
</div>
@endsection
