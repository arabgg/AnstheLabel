<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FotoProdukModel extends Model
{
    use HasFactory;

    protected $table = 't_foto_produk';
    protected $primaryKey = 'foto_produk_id';
    
    protected $fillable = [
        'produk_id',
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
}
