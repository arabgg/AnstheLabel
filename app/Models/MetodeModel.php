<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MetodeModel extends Model
{
    use HasFactory;

    protected $table = 'm_metode_pembayaran';
    protected $primaryKey = 'metode_id';
    
    protected $fillable = [
        'nama_metode',
        'kode_bayar',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function pembayaran() :HasMany
    {
        return $this->hasMany(PembayaranModel::class, 'metode_id', 'metode_id');
    }

    public function mPembayaran() :HasMany
    {
        return $this->hasMany(MetodePembayaranModel::class, 'metode_id', 'metode_id');
    }
}
