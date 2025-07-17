<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriModel extends Model
{
    use HasFactory;

    protected $table = 'm_kategori';
    protected $primaryKey = 'kategori_id';
    
    protected $fillable = [
        'nama_kategori_halaman',
        'deskripsi'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function produk() :HasMany
    {
        return $this->hasMany(ProdukModel::class, 'kategori_id', 'kategori_id');
    }
}
