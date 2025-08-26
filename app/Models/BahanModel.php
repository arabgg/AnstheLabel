<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BahanModel extends Model
{
    use HasFactory;

    protected $table = 'm_bahan';
    protected $primaryKey = 'bahan_id';
    
    protected $fillable = [
        'nama_bahan',
        'deskripsi',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function produk() :HasMany
    {
        return $this->hasMany(ProdukModel::class, 'bahan_id', 'bahan_id');
    }
}
