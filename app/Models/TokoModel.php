<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TokoModel extends Model
{
    use HasFactory;

    protected $table = 'm_toko';
    protected $primaryKey = 'toko_id';
    
    protected $fillable = [
        'nama_toko',
        'icon_toko',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function toko() :HasMany
    {
        return $this->hasMany(TokoProdukModel::class, 'toko_id', 'toko_id');
    }
}
