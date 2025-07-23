<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BahanProdukModel extends Model
{
    use HasFactory;

    protected $table = 'm_bahan_produk';
    protected $primaryKey = 'bahan_produk_id';
    
    protected $fillable = [
        'nama_bahan',
        'deskripsi',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function detail() :HasMany
    {
        return $this->hasMany(DetailProdukModel::class, 'bahan_produk_id', 'bahan_produk_id');
    }
}
