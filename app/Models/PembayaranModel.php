<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class PembayaranModel extends Model
{
    use HasFactory;

    protected $table = 't_pembayaran';
    protected $primaryKey = 'pembayaran_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'metode_pembayaran_id',
        'status_pembayaran',
        'jumlah_produk',
        'total_harga',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->pembayaran_id)) {
                $model->pembayaran_id = (string) Str::uuid();
            }
        });
    }

    public function metode() :BelongsTo
    {
        return $this->belongsTo(MetodeModel::class, 'metode_id', 'metode_id');
    }

    public function detail() :HasMany
    {
        return $this->hasMany(DetailTransaksiModel::class, 'pembayaran_id', 'pembayaran_id');
    }

    public function pembayaran() :HasOne
    {
        return $this->hasOne(TransaksiModel::class, 'pembayaran_id', 'pembayaran_id');
    }
}
