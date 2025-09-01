<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;
use Carbon\CarbonPeriod;

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
        return $this->belongsTo(MetodePembayaranModel::class, 'metode_pembayaran_id', 'metode_pembayaran_id');
    }

    public function detail() :HasMany
    {
        return $this->hasMany(DetailTransaksiModel::class, 'pembayaran_id', 'pembayaran_id');
    }

    public function pembayaran() :HasOne
    {
        return $this->hasOne(TransaksiModel::class, 'pembayaran_id', 'pembayaran_id');
    }

    public static function getPendapatanHarianByRange($startDate, $endDate)
    {
        $data = self::selectRaw('DATE(created_at) as tanggal, SUM(CAST(total_harga AS UNSIGNED)) as pendapatan')
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get()
            ->keyBy('tanggal');

        $period = CarbonPeriod::create($startDate, $endDate);
        $result = [];
        foreach ($period as $date) {
            $day = $date->format('Y-m-d');
            $result[] = [
                'tanggal' => $day,
                'pendapatan' => isset($data[$day]) ? $data[$day]->pendapatan : 0
            ];
        }
        return collect($result);
    }
}
