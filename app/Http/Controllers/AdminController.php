<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\PembayaranModel;
use App\Models\DetailTransaksiModel;
use App\Models\ProdukModel;
use App\Models\TransaksiModel;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {

        $pendapatan = PembayaranModel::select('pembayaran_id', 'total_harga')
            ->where('status_pembayaran', 'lunas')
            ->get()
            ->sum(fn($t) => $t->total_harga ?? 0);

        $orderSelesai = TransaksiModel::select('transaksi_id', 'status_transaksi')
            ->where('status_transaksi', 'selesai')->count();

        $orderProses = TransaksiModel::select('transaksi_id', 'status_transaksi')
            ->where('status_transaksi', '!=', 'selesai')->count();

        $produk = ProdukModel::select('produk_id')->count();

        $revenueChart = [];
        $ordersChart = [];

        for ($i = 10; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $formattedDate = $date->format('M d');

            $dailyRevenue = TransaksiModel::select('transaksi_id', 'pembayaran_id')
                ->whereRelation('pembayaran', 'status_pembayaran', 'lunas')
                ->whereDate('created_at', $date) 
                ->with('pembayaran')
                ->get()
                ->sum(fn($t) => $t->pembayaran->total_harga ?? 0);

            $dailyOrders = TransaksiModel::select('transaksi_id', 'status_transaksi')
                ->where('status_transaksi', 'selesai')
                ->whereDate('created_at', $date)
                ->count();

            $revenueChart[$formattedDate] = $dailyRevenue;
            $ordersChart[$formattedDate] = $dailyOrders;
        }

        $orders = TransaksiModel::select('transaksi_id', 'pembayaran_id', 'kode_invoice', 'nama_customer', 'status_transaksi', 'created_at')
            ->with([
                'pembayaran:pembayaran_id,metode_pembayaran_id,total_harga,status_pembayaran',
                'pembayaran.metode:metode_pembayaran_id,nama_pembayaran',
            ])
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('pendapatan', 'orderSelesai', 'orderProses', 'produk', 'revenueChart', 'ordersChart', 'orders'));

    }
}