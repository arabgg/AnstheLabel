<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProdukModel extends Model
{
    use HasFactory;

    protected $table = 't_produk';
    protected $primaryKey = 'produk_id';
    
    protected $fillable = [
        'kategori_id',
        'detail_produk_id',
        'nama_produk',
        'harga',
        'foto_produk',
        'warna'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function kategori() :BelongsTo
    {
        return $this->belongsTo(KategoriModel::class, 'kategori_id', 'kategori_id');
    }

    public function detail() :BelongsTo
    {
        return $this->belongsTo(DetailProdukModel::class, 'detail_produk_id', 'detail_produk_id');
    }
}
