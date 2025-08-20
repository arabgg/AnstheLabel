<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Ramsey\Uuid\Type\Decimal;

class ProdukModel extends Model
{
    use HasFactory;

    protected $table = 't_produk';
    protected $primaryKey = 'produk_id';
    
    protected $fillable = [
        'kategori_id',
        'bahan_id',
        'nama_produk',
        'harga',
        'diskon',
        'deskripsi',
        'harga',
        'diskon',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getHargaDiskonAttribute()
    {
        $harga = (string) ($this->harga ?? '0.00');
        $diskon = (string) ($this->diskon ?? '0.00');

        // Kurangi dengan presisi 2 digit desimal
        $total = bcsub($harga, $diskon, 2);

        // Pastikan tidak negatif
        return max((float) $total, 0);
    }

    public function getDiskonPersenAttribute()
    {
        $harga  = (float) ($this->harga ?? 0);
        $diskon = (float) ($this->diskon ?? 0);

        if ($harga > 0 && $diskon > 0) {
            return (float) bcmul(
                bcdiv((string) $diskon, (string) $harga, 4),
                '100',
                0
            );
        }

        return 0;
    }

    public function kategori() :BelongsTo
    {
        return $this->belongsTo(KategoriModel::class, 'kategori_id', 'kategori_id');
    }

    public function bahan() :BelongsTo
    {
        return $this->belongsTo(BahanModel::class, 'bahan_id', 'bahan_id');
    }

    public function foto() :HasMany
    {
        return $this->hasMany(FotoProdukModel::class, 'produk_id', 'produk_id');
    }

    public function fotoUtama()
    {
        return $this->hasOne(FotoProdukModel::class, 'produk_id', 'produk_id')
            ->where('status_foto', 1);
    }

    public function warna() :HasMany
    {
        return $this->hasMany(WarnaProdukModel::class, 'produk_id', 'produk_id');
    }

    public function ukuran() :HasMany
    {
        return $this->hasMany(UkuranProdukModel::class, 'produk_id', 'produk_id');
    }

    public function detailTransaksi() :HasMany
    {
        return $this->hasMany(DetailTransaksiModel::class, 'produk_id', 'produk_id');
    }
}
