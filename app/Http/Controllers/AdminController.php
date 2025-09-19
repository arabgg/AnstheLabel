<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PembayaranModel;
use App\Models\ProdukModel;
use App\Models\TransaksiModel;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        // Ambil tahun-tahun unik dari transaksi
        $availableYears = TransaksiModel::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->toArray();

        // Default: tahun terbaru dan mode bulanan
        $selectedYear = $request->input('year', $availableYears[0] ?? date('Y'));
        $viewMode = $request->input('view_mode', 'monthly');

        $pendapatan = PembayaranModel::where('status_pembayaran', 'lunas')->sum('total_harga');
        $orderSelesai = TransaksiModel::where('status_transaksi', 'selesai')->count();
        $orderProses  = TransaksiModel::where('status_transaksi', '!=', 'selesai')->count();
        $produk       = ProdukModel::count();

        $revenueChart = [];
        $ordersChart  = [];

        if ($viewMode === 'monthly') {
            for ($i = 1; $i <= 12; $i++) {
                $label = Carbon::create($selectedYear, $i)->format('F');

                $monthlyRevenue = TransaksiModel::whereMonth('created_at', $i)
                    ->whereYear('created_at', $selectedYear)
                    ->whereRelation('pembayaran', 'status_pembayaran', 'lunas')
                    ->with('pembayaran')
                    ->get()
                    ->sum(fn($t) => $t->pembayaran->total_harga ?? 0);

                $monthlyOrders = TransaksiModel::whereMonth('created_at', $i)
                    ->whereYear('created_at', $selectedYear)
                    ->where('status_transaksi', 'selesai')
                    ->count();

                $revenueChart[$label] = $monthlyRevenue;
                $ordersChart[$label]  = $monthlyOrders;
            }
        } elseif ($viewMode === 'quarterly') {
            $quarters = [
                'Q1' => [1, 2, 3],
                'Q2' => [4, 5, 6],
                'Q3' => [7, 8, 9],
                'Q4' => [10, 11, 12],
            ];

            foreach ($quarters as $label => $months) {
                $quarterRevenue = TransaksiModel::whereIn(DB::raw('MONTH(created_at)'), $months)
                    ->whereYear('created_at', $selectedYear)
                    ->whereRelation('pembayaran', 'status_pembayaran', 'lunas')
                    ->with('pembayaran')
                    ->get()
                    ->sum(fn($t) => $t->pembayaran->total_harga ?? 0);

                $quarterOrders = TransaksiModel::whereIn(DB::raw('MONTH(created_at)'), $months)
                    ->whereYear('created_at', $selectedYear)
                    ->where('status_transaksi', 'selesai')
                    ->count();

                $revenueChart[$label] = $quarterRevenue;
                $ordersChart[$label]  = $quarterOrders;
            }
        }

        $orders = TransaksiModel::with([
            'pembayaran:pembayaran_id,metode_pembayaran_id,total_harga,status_pembayaran',
            'pembayaran.metode:metode_pembayaran_id,nama_pembayaran',
        ])
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'pendapatan',
            'orderSelesai',
            'orderProses',
            'produk',
            'revenueChart',
            'ordersChart',
            'orders',
            'viewMode',
            'selectedYear',
            'availableYears'
        ));
    }
}