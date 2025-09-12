<?php

namespace App\Http\Controllers;

use App\Models\PembayaranModel;
use App\Models\TransaksiModel;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    public function index(Request $request)
    {
        $searchQuery = $request->input('search');
        $status = $request->input('status');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = TransaksiModel::query();

        // Search
        $query->when(!empty($searchQuery), function ($q) use ($searchQuery) {
            $q->where(function ($sub) use ($searchQuery) {
                $sub->where('nama_customer', 'like', "%{$searchQuery}%")
                    ->orWhere('email', 'like', "%{$searchQuery}%")
                    ->orWhere('kode_invoice', 'like', "%{$searchQuery}%")
                    ->orWhere('no_telp', 'like', "%{$searchQuery}%");
            });
        });

        // Status
        $query->when(!empty($status), function ($q) use ($status) {
            $q->where('status_transaksi', $status);
        });

        // Filter tanggal fleksibel
        $query->when($startDate && $endDate, function ($q) use ($startDate, $endDate) {
            $q->whereBetween('created_at', [
                $startDate . ' 00:00:00',
                $endDate . ' 23:59:59'
            ]);
        })
            ->when($startDate && !$endDate, function ($q) use ($startDate) {
                $q->whereDate('created_at', '>=', $startDate);
            })
            ->when(!$startDate && $endDate, function ($q) use ($endDate) {
                $q->whereDate('created_at', '<=', $endDate);
            });

        // Ambil data
        $pesanan = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends($request->query());

        return view('admin.pesanan.index', compact('pesanan', 'searchQuery', 'status', 'startDate', 'endDate'));
    }

    public function show($id)
    {
        $transaksi = TransaksiModel::with([
            'pembayaran.metode', // relasi ke metode pembayaran
            'detail.produk',
            'detail.ukuran',
            'detail.warna'
        ])->findOrFail($id);

        $total = 0;
        $total += $transaksi->pembayaran->total_harga;

        return view('admin.pesanan.show', compact('transaksi', 'total'));
    }

    public function updateTransaksi(Request $request, $id)
    {
        $request->validate([
            'status_transaksi' => 'required|in:menunggu pembayaran,dikemas,dikirim,selesai,batal',
        ]);

        $transaksi = TransaksiModel::findOrFail($id);
        $transaksi->status_transaksi = $request->status_transaksi;
        $transaksi->save();

        // Update status pembayaran sesuai transaksi
        $pembayaran = $transaksi->pembayaran; // pastikan relasi transaksi -> pembayaran sudah ada

        if ($pembayaran) {
            if (in_array($transaksi->status_transaksi, ['dikemas', 'dikirim', 'selesai'])) {
                $pembayaran->status_pembayaran = 'Lunas';
            } elseif ($transaksi->status_transaksi === 'batal') {
                $pembayaran->status_pembayaran = 'Dibatalkan';
            } else {
                $pembayaran->status_pembayaran = 'Menunggu Pembayaran';
            }

            $pembayaran->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Status transaksi & pembayaran berhasil diperbarui',
            'data' => [
                'transaksi' => $transaksi,
                'pembayaran' => $pembayaran ?? null,
            ]
        ]);
    }
}
