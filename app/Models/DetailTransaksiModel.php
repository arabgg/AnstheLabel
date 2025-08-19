<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailTransaksiModel extends Model
{
    use HasFactory;

    protected $table = 't_detail_transaksi';
    protected $primaryKey = 'detail_transaksi_id';

    protected $fillable = [
        'transaksi_id',
        'pembayaran_id',
        'produk_id',
        'ukuran_id',
        'warna_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function transaksi() :BelongsTo
    {
        return $this->belongsTo(TransaksiModel::class, 'transaksi_id', 'transaksi_id');
    }

    public function pembayaran() :BelongsTo
    {
        return $this->belongsTo(PembayaranModel::class, 'pembayaran_id', 'pembayaran_id');
    }

    public function produk() :BelongsTo
    {
        return $this->belongsTo(TransaksiModel::class, 'transaksi_id', 'transaksi_id');
    }

    public function ukuran() :BelongsTo
    {
        return $this->belongsTo(TransaksiModel::class, 'transaksi_id', 'transaksi_id');
    }

    public function warna() :BelongsTo
    {
        return $this->belongsTo(TransaksiModel::class, 'transaksi_id', 'transaksi_id');
    }
}
