<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EkspedisiModel extends Model
{
    use HasFactory;

    protected $table = 'm_ekspedisi';
    protected $primaryKey = 'ekspedisi_id';
    
    protected $fillable = [
        'nama_ekspedisi',
        'status_ekspedisi',
        'icon',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function ekspedisi() :HasMany 
    {
        return $this->hasMany(TransaksiModel::class, 'ekspedisi_id', 'ekspedisi_id');
    }
}
