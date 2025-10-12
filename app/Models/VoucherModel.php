<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function voucher() :HasMany 
    {
        return $this->hasMany(PembayaranModel::class, 'voucher_id', 'voucher_id');
    }

    public function isValid($totalBelanja): bool
    {
        $now = Carbon::now();

        if (!$this->status_voucher) {
            return false;
        }

        if ($this->tanggal_mulai && $now->lt($this->tanggal_mulai)) {
            return false;
        }

        if ($this->tanggal_berakhir && $now->gt($this->tanggal_berakhir)) {
            return false;
        }

        if ($this->usage_limit !== null && $this->used >= $this->usage_limit) {
            return false;
        }

        if ($this->min_transaksi && $totalBelanja < $this->min_transaksi) {
            return false;
        }

        return true;
    }

    public function hitungPotongan($totalBelanja): float
    {
        if (!$this->isValid($totalBelanja)) {
            return 0;
        }

        if ($this->tipe_diskon === 'persen') {
            return ($this->nilai_diskon) * $totalBelanja;
        }

        if ($this->tipe_diskon === 'nominal') {
            return (float) $this->nilai_diskon;
        }

        return 0;
    }

    public function markAsUsed(): void
    {
        $this->increment('used');

        if (!is_null($this->usage_limit) && $this->used >= $this->usage_limit) {
            $this->update(['status_voucher' => false]);
        }
    }
}
