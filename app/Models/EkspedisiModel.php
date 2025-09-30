<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EkspedisiModel extends Model
{
    use HasFactory;

    protected $table = 'm_ekspedisi';
    protected $primaryKey = 'ekspedisi_id';
    
    protected $fillable = [
        'nama_ekspedisi',
        'status_pembayaran',
        'icon',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
