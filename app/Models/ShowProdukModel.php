<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShowProdukModel extends Model
{
    use HasFactory;

    protected $table = 't_show_produk';
    protected $primaryKey = 'show_produk_id';
    
    protected $fillable = [
        'produk_id',
        'detail_produk_id',
        'warna_produk_id',
        'foto_produk',
        'status_foto',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function produk() :BelongsTo
    {
        return $this->belongsTo(ProdukModel::class, 'produk_id', 'produk_id');
    }

    public function detail() :BelongsTo
    {
        return $this->belongsTo(DetailProdukModel::class, 'detail_produk_id', 'detail_produk_id');
    }

    public function warna() :BelongsTo
    {
        return $this->belongsTo(WarnaProdukModel::class, 'warna_produk_id', 'warna_produk_id');
    }
}
