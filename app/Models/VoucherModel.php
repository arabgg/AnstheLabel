<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherModel extends Model
{
    use HasFactory;

    protected $table = 'm_voucher';
    protected $primaryKey = 'voucher_id';

    protected $fillable = [
        'kode_voucher',
        'deskripsi',
        'tipe_diskon',
        'nilai_diskon',
        'min_transaksi',
        'usage_limit',
        'used',
        'status_voucher',
        'tanggal_mulai',
        'tanggal_berakhir',
    ];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_berakhir' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'status_voucher' => 'boolean',
    ];
}