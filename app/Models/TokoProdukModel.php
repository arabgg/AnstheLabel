<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TokoProdukModel extends Model
{
    use HasFactory;

    protected $table = 't_toko_produk';
    protected $primaryKey = 'toko_produk_id';
    
    protected $fillable = [
        'produk_id',
        'toko_id',
        'url_toko',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function produk() :BelongsTo
    {
        return $this->belongsTo(ProdukModel::class, 'produk_id', 'produk_id');
    }

    public function toko() :BelongsTo
    {
        return $this->belongsTo(TokoModel::class, 'toko_id', 'toko_id');
    }
}
