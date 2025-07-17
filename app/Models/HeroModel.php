<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HeroModel extends Model
{
    use HasFactory;

    protected $table = 't_hero';
    protected $primaryKey = 'hero_id';
    
    protected $fillable = [
        'kategori_hero_id',
        'gambar_hero',
        'keterangan'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function kategori() :BelongsTo
    {
        return $this->belongsTo(KategoriHerModel::class, 'kategori_halaman_id', 'kategori_halaman_id');
    }
}
