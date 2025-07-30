<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UkuranProdukModel extends Model
{
    use HasFactory;

    protected $table = 't_ukuran_produk';
    protected $primaryKey = 'ukuran_produk_id';
    
    protected $fillable = [
        'produk_id',
        'ukuran_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function produk() :BelongsTo
    {
        return $this->belongsTo(ProdukModel::class, 'produk_id', 'produk_id');
    }

    public function ukuran() :BelongsTo
    {
        return $this->belongsTo(UkuranModel::class, 'ukuran_id', 'ukuran_id');
    }
}
