<?php

namespace App\Models;

use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class DetailTransaksiModel extends Model
{
    use HasFactory;

    protected $table = 't_detail_transaksi';
    protected $primaryKey = 'detail_transaksi_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'transaksi_id',
        'pembayaran_id',
        'produk_id',
        'ukuran_id',
        'warna_id',
        'jumlah',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->detail_transaksi_id)) {
                $model->detail_transaksi_id = (string) Str::uuid();
            }
        });
    }

    public function transaksi() :BelongsTo
    {
        return $this->belongsTo(TransaksiModel::class, 'transaksi_id', 'transaksi_id');
    }

    public function pembayaran() :BelongsTo
    {
        return $this->belongsTo(PembayaranModel::class, 'pembayaran_id', 'pembayaran_id');
    }

    public function produk() :BelongsTo
    {
        return $this->belongsTo(ProdukModel::class, 'produk_id', 'produk_id');
    }

    public function ukuran() :BelongsTo
    {
        return $this->belongsTo(UkuranModel::class, 'ukuran_id', 'ukuran_id');
    }

    public function warna() :BelongsTo
    {
        return $this->belongsTo(WarnaModel::class, 'warna_id', 'warna_id');
    }

    public static function getProdukTerjualHarianByRange($startDate, $endDate)
    {
        $data = self::selectRaw('DATE(created_at) as tanggal, SUM(jumlah) as produk_terjual')
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
                'produk_terjual' => isset($data[$day]) ? $data[$day]->produk_terjual : 0
            ];
        }
        return collect($result);
    }
}
