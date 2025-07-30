<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UkuranModel extends Model
{
    use HasFactory;

    protected $table = 'm_ukuran';
    protected $primaryKey = 'ukuran_id';
    
    protected $fillable = [
        'nama_ukuran',
        'deskripsi',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function ukuran() :HasMany
    {
        return $this->hasMany(UkuranProdukModel::class, 'ukuran_id', 'ukuran_id');
    }
}
