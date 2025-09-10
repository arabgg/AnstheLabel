@extends('admin.layouts.app')

@section('content')
    <div class="p-6">
        {{-- Header Invoice --}}
        <div class="bg-white shadow-lg rounded-xl p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h1 class="text-2xl font-bold">Detail Pesanan</h1>
                <a href="{{ url('/pesanan') }}"
                    class="px-4 py-2 bg-[#560024] text-white rounded-lg shadow hover:bg-[#7a0033] transition">
                    ← Kembali
                </a>
            </div>

            <p class="text-gray-600">Kode Invoice: <span class="font-semibold">{{ $transaksi->kode_invoice }}</span></p>
            <hr class="my-4">

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <p><strong>Customer:</strong> {{ $transaksi->nama_customer }}</p>
                    <p><strong>No Telp:</strong> {{ $transaksi->no_telp }}</p>
                    <p><strong>Email:</strong> {{ $transaksi->email }}</p>
                    <p><strong>Alamat:</strong> {{ $transaksi->alamat }}</p>
                </div>
                <div>
                    <p><strong>Status Transaksi:</strong>
                        <span
                            class="px-2 py-1 rounded-lg 
                        {{ $transaksi->status_transaksi == 'selesai' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                            {{ ucfirst($transaksi->status_transaksi) }}
                        </span>
                    </p>
                    <p><strong>Pembayaran:</strong> {{ $transaksi->pembayaran->metode->nama_pembayaran ?? '-' }}</p>
                </div>
            </div>
        </div>

        {{-- Produk Detail --}}
        <div class="bg-white shadow-lg rounded-xl p-6">
            <h2 class="text-xl font-semibold mb-4">Produk Dipesan</h2>

            <div class="grid gap-5 grid-cols-[repeat(auto-fill,minmax(220px,1fr))]">
                @foreach ($transaksi->detail as $detail)
                    <div class="border rounded-xl shadow-sm overflow-hidden bg-gray-50">
                        {{-- Foto Produk --}}
                        <div class="aspect-[4/5] rounded-xl overflow-hidden">
                            <img src="{{ route('storage', ['folder' => 'foto_produk', 'filename' => $detail->produk->fotoUtama->foto_produk]) }}"
                                alt="{{ optional($detail->produk)->nama_produk ?? 'Produk' }}"
                                class="w-full h-full object-cover">
                        </div>

                        {{-- Info Produk --}}
                        <div class="p-4">
                            <h3 class="font-semibold text-lg">{{ $detail->produk->nama_produk ?? '-' }}</h3>
                            <p class="text-sm text-gray-600">Ukuran: {{ $detail->ukuran->nama_ukuran ?? '-' }}</p>
                            <p class="text-sm text-gray-600">Warna: {{ $detail->warna->nama_warna ?? '-' }}</p>

                            <div class="flex justify-between items-center mt-4">
                                <span class="font-bold">Jumlah: {{ $detail->jumlah }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
