<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\PembayaranModel;
use App\Models\DetailTransaksiModel;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Dashboard';

        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : now()->subDays(6);
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : now();

        // Validasi maksimal 7 hari
        if ($startDate->diffInDays($endDate) > 6) {
            $endDate = $startDate->copy()->addDays(6);
        }

        // Ambil data
        $pendapatanHarian = PembayaranModel::getPendapatanHarianByRange($startDate, $endDate);
        $produkHarian = DetailTransaksiModel::getProdukTerjualHarianByRange($startDate, $endDate);

        return view('admin.dashboard', [
            'title' => $title,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'pendapatanHarian' => $pendapatanHarian,
            'produkHarian' => $produkHarian,
            'totalPendapatan' => $pendapatanHarian->sum('pendapatan'),
            'totalProduk' => $produkHarian->sum('produk_terjual'),
        ]);
    }
}