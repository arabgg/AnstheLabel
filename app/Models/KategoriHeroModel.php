<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriHerModel extends Model
{
    use HasFactory;

    protected $table = 'm_kategori_hero';
    protected $primaryKey = 'kategori_hero_id';
    
    protected $fillable = [
        'nama_kategori_hero',
        'deskripsi'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function hero() :HasMany
    {
        return $this->hasMany(HeroModel::class, 'kategori_halaman_id', 'kategori_halaman_id');
    }
}
