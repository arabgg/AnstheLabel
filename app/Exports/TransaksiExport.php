<?php

namespace App\Exports;

use App\Models\TransaksiModel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class TransaksiExport implements FromView
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function view(): View
    {
        $query = TransaksiModel::with(['pembayaran.metode', 'detail.produk', 'detail.ukuran', 'detail.warna']);

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('created_at', [
                $this->startDate . ' 00:00:00',
                $this->endDate . ' 23:59:59',
            ]);
        } elseif ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        } elseif ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        $transaksi = $query->orderBy('created_at', 'desc')->get();

        return view('admin.exports.transaksi', compact('transaksi'));
    }
}
