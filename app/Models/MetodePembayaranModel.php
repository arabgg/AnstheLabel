<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MetodePembayaranModel extends Model
{
    use HasFactory;

    protected $table = 't_metode_pembayaran';
    protected $primaryKey = 'metode_pembayaran_id';
    
    protected $fillable = [
        'metode_id',
        'nama_pembayaran',
        'kode_bayar',
        'status_pembayaran',
        'icon',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function metode() :BelongsTo
    {
        return $this->belongsTo(MetodeModel::class, 'metode_id', 'metode_id');
    }

    public function pembayaran() :HasMany
    {
        return $this->hasMany(PembayaranModel::class, 'metode_pembayaran_id', 'metode_pembayaran_id');
    }

    public function getKodeBayarTypeAttribute()
    {
        if (!$this->kode_bayar) {
            return 'empty';
        }

        $ext = strtolower(pathinfo($this->kode_bayar, PATHINFO_EXTENSION));

        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
            return 'image';
        }

        return 'text';
    }
}
