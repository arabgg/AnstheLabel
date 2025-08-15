<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembayaranModel extends Model
{
    use HasFactory;

    protected $table = 't_pembayaran';
    protected $primaryKey = 'pembayaran_id';

    protected $fillable = [
        'metode_id',
        'status_pembayaran',
        'jumlah_produk',
        'total_harga',
        'alamat',
        'status_transaksi',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function metode() :BelongsTo
    {
        return $this->belongsTo(MetodeModel::class, 'metode_id', 'metode_id');
    }
}
