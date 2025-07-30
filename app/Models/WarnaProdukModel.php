<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WarnaProdukModel extends Model
{
    use HasFactory;

    protected $table = 't_warna_produk';
    protected $primaryKey = 'warna_produk_id';
    
    protected $fillable = [
        'produk_id',
        'warna_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function produk() :BelongsTo
    {
        return $this->belongsTo(ProdukModel::class, 'produk_id', 'produk_id');
    }

    public function warna() :BelongsTo
    {
        return $this->belongsTo(WarnaModel::class, 'warna_id', 'warna_id');
    }
}
