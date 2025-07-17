<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DetailProdukModel extends Model
{
    use HasFactory;

    protected $table = 'm_detail_produk';
    protected $primaryKey = 'detail_produk_id';
    
    protected $fillable = [
        'ukuran',
        'warna'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function produk() :HasMany
    {
        return $this->hasMany(ProdukModel::class, 'detail_produk_id', 'detail_produk_id');
    }
}
