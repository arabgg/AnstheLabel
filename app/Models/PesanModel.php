<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesanModel extends Model
{
    use HasFactory;

    protected $table = 't_pesan';
    protected $primaryKey = 'pesan_id';
    
    protected $fillable = [
        'email',
        'pesan'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
