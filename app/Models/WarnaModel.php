<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WarnaModel extends Model
{
    use HasFactory;

    protected $table = 'm_warna';
    protected $primaryKey = 'warna_id';
    
    protected $fillable = [
        'nama_warna',
        'deskripsi',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function warna() :HasMany
    {
        return $this->hasMany(WarnaProdukModel::class, 'warna_id', 'warna_id');
    }
}
