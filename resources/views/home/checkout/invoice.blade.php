@extends('home.layouts.app')

@section('content')
<div class="invoice-container">
    <h2 class="invoice-title">{{ __('messages.title.invoice') }}</h2>
    <h2 class="invoice-sub-title">{{ __('messages.title.sub_invoice') }}</h2>

    @if(session('error'))
        <div class="invoice-error">{{ session('error') }}</div>
    @endif

    <form action="{{ route('invoice.cek') }}" method="POST" class="invoice-form">
        <label for="kode_invoice" class="invoice-label">{{ __('messages.label.invoice') }}</label>
        @csrf
        <input type="text" name="kode_invoice" placeholder="Masukkan no Invoice Anda (Contoh:ANS-XXXXXXXX-XXXXXXXXX)" id="kode_invoice" class="invoice-input" required>
        <button type="submit" class="invoice-btn"><i class="fa-solid fa-magnifying-glass"></i> {{ __('messages.button.invoice') }}</button>
    </form>
</div>
@endsection