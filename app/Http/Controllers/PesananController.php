<?php

namespace App\Http\Controllers;

use App\Models\PembayaranModel;
use App\Models\TransaksiModel;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    public function index(Request $request)
    {
        $searchQuery = $request->input('search', '');

        $pesanan = TransaksiModel::select('transaksi_id', 'pembayaran_id', 'kode_invoice', 'nama_customer', 'no_telp', 'email', 'alamat', 'status_transaksi')
            ->with([
                'pembayaran:pembayaran_id,metode_pembayaran_id,status_pembayaran',
                'pembayaran.metode:metode_pembayaran_id,nama_pembayaran'
            ])
            ->when(!empty($searchQuery), function ($q) use ($searchQuery) {
                $q->where('kode_invoice', 'like', "%{$searchQuery}%");
            })
            ->orderBy('transaksi_id', 'asc')
            ->paginate(10);

        return view('admin.pesanan.index', compact('pesanan', 'searchQuery'));
    }

    public function show($id) {
        $transaksi = TransaksiModel::with([
            'pembayaran.metode', // relasi ke metode pembayaran
            'detail.produk',
            'detail.ukuran',
            'detail.warna'
        ])->findOrFail($id);
        return view('admin.pesanan.show', compact('transaksi'));
    }

    public function updatePembayaran(Request $request, $id)
    {
        $request->validate([
            'status_pembayaran' => 'required|in:pending,lunas,gagal',
        ]);

        $pembayaran = PembayaranModel::findOrFail($id);
        $pembayaran->status_pembayaran = $request->status_pembayaran;
        $pembayaran->save();

        return response()->json([
            'success' => true,
            'message' => 'Status pembayaran berhasil diperbarui',
            'data' => $pembayaran
        ]);
    }

    public function updateTransaksi(Request $request, $id)
    {
        $request->validate([
            'status_transaksi' => 'required|in:menunggu pembayaran,dikemas,dikirim,selesai,batal',
        ]);

        $transaksi = TransaksiModel::findOrFail($id);
        $transaksi->status_transaksi = $request->status_transaksi;
        $transaksi->save();

        return response()->json([
            'success' => true,
            'message' => 'Status transaksi berhasil diperbarui',
            'data' => $transaksi
        ]);
    }
}
