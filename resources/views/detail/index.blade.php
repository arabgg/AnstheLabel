@extends('layouts.app')

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('page') }}">Home</a> / 
        <a href="{{ route('collection') }}">Collection</a> / 
        <span>{{ $detail->produk->nama_produk }}</span>
    </div>
@endsection

@section('content')
@endsection

@push('scripts')
@endpush