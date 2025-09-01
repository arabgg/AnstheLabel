<?php

namespace App\Http\Controllers;

use App\Models\TransaksiModel;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransaksiExport;

class BannerController extends Controller
{
    // Menampilkan semua transaksi
    public function index()
    {
        $transaksis = TransaksiModel::with('pembayaran', 'detail')->latest()->get();
        return view('admin.transaksi.index', compact('transaksis'));
    }

    // Menampilkan detail transaksi
    public function show($id)
    {
        $transaksi = TransaksiModel::with('pembayaran', 'detail')->findOrFail($id);
        return view('transaksi.show', compact('transaksi'));
    }

    // Form edit status transaksi
    public function edit($id)
    {
        $transaksi = TransaksiModel::findOrFail($id);
        return view('transaksi.edit', compact('transaksi'));
    }

    // Update hanya status transaksi
    public function update(Request $request, $id)
    {
        $request->validate([
            'status_transaksi' => 'required|in:pending,success,cancelled',
        ]);

        $transaksi = TransaksiModel::findOrFail($id);
        $transaksi->update(['status_transaksi' => $request->status_transaksi]);

        return redirect()->route('transaksi.index')->with('success', 'Status transaksi berhasil diperbarui');
    }

    // Export data transaksi
    public function export()
    {
        return Excel::download(new TransaksiExport, 'transaksi.xlsx');
    }
}
