<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiModel extends Model
{
    use HasFactory;

    protected $table = 't_transaksi';
    protected $primaryKey = 'transaksi_id';
    
    protected $fillable = [
        'kode_invoice',
        'nama_customer',
        'no_telp',
        'email',
        'alamat',
        'status_transaksi',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public static function generateInvoiceNumber($id)
    {
        $prefix = 'ANS';
        $date = Carbon::now()->format('Ymd');
        return $prefix . '-' . $date . '-' . $id;
    }
}
