<?php

namespace App\Exports;

use App\Models\TransaksiModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransaksiExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return TransaksiModel::select(
            'kode_invoice',
            'nama_customer',
            'no_telp',
            'email',
            'alamat',
            'status_transaksi',
            'created_at'
        )->get();
    }

    public function headings(): array
    {
        return [
            'Kode Invoice',
            'Nama Customer',
            'No Telepon',
            'Email',
            'Alamat',
            'Status Transaksi',
            'Tanggal Transaksi'
        ];
    }
}
