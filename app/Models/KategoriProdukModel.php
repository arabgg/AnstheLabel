<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriProdukModel extends Model
{
    use HasFactory;

    protected $table = 'm_kategori_produk';
    protected $primaryKey = 'kategori_produk_id';
    
    protected $fillable = [
        'nama_kategori',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function produk() :HasMany
    {
        return $this->hasMany(ProdukModel::class, 'kategori_produk_id', 'kategori_produk_id');
    }
}
