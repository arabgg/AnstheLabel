<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetodeModel extends Model
{
    use HasFactory;

    protected $table = 'm_metode_pembayaran';
    protected $primaryKey = 'bahan_id';
    
    protected $fillable = [
        'nama_bahan',
        'deskripsi',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function produk() :HasMany
    {
        return $this->hasMany(ProdukModel::class, 'bahan_id', 'bahan_id');
    }
}
