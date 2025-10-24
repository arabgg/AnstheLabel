<?php

namespace App\Http\Controllers;

use App\Models\BannerModel;
use App\Models\TransaksiModel;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function invoice()
    {
        $desc = BannerModel::select('banner_id', 'deskripsi')
            ->where('banner_id', 19)
            ->first();

        return view('home.checkout.invoice', compact('desc'));
    }

    public function cekInvoice(Request $request)
    {
        $request->validate([
            'kode_invoice' => 'required|string'
        ]);

        $transaksi = TransaksiModel::select('transaksi_id', 'kode_invoice')
            ->where('kode_invoice', $request->kode_invoice)
            ->first();

        if ($transaksi) {
            return redirect()->route('transaksi.show', $transaksi->kode_invoice);
        }

        return back()->with('error', 'Kode Invoice tidak ditemukan');
    }
}
