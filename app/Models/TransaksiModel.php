<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class TransaksiModel extends Model
{
    use HasFactory;

    protected $table = 't_transaksi';
    protected $primaryKey = 'transaksi_id';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'kode_invoice',
        'nama_customer',
        'no_telp',
        'email',
        'alamat',
        'status_transaksi',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function booted()
    {
        // Generate UUID dan kode_invoice sebelum insert
        static::creating(function ($model) {
            if (empty($model->transaksi_id)) {
                $model->transaksi_id = (string) Str::uuid();
            }
        });

        // Generate kode_invoice setelah insert (butuh transaksi_id)
        static::created(function ($model) {
            $kodeInvoice = 'ANS-' . Carbon::now()->format('Ymd') . '-' . substr($model->transaksi_id, 0, 8);
            $model->update(['kode_invoice' => $kodeInvoice]);
        });
    }

    public function detail() :HasMany
    {
        return $this->hasMany(DetailTransaksiModel::class, 'transaksi_id', 'transaksi_id');
    }
}
