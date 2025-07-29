<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProdukModel extends Model
{
    use HasFactory;

    protected $table = 't_produk';
    protected $primaryKey = 'produk_id';
    
    protected $fillable = [
        'kategori_produk_id',
        'toko_produk_id',
        'nama_produk',
        'deskripsi',
        'url_toko'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function kategori() :BelongsTo
    {
        return $this->belongsTo(KategoriProdukModel::class, 'kategori_produk_id', 'kategori_produk_id');
    }

    public function toko() :BelongsTo
    {
        return $this->belongsTo(DetailProdukModel::class, 'detail_produk_id', 'detail_produk_id');
    }

    public function show() :HasMany
    {
        return $this->hasMany(ShowProdukModel::class, 'produk_id', 'produk_id');
    }
}
