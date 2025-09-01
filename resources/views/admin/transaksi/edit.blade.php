@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h1>Edit Status Transaksi</h1>
    <form action="{{ route('transaksi.update', $transaksi->transaksi_id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Status Transaksi</label>
            <select name="status_transaksi" class="form-control">
                <option value="pending" {{ $transaksi->status_transaksi == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="success" {{ $transaksi->status_transaksi == 'success' ? 'selected' : '' }}>Success</option>
                <option value="cancelled" {{ $transaksi->status_transaksi == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
