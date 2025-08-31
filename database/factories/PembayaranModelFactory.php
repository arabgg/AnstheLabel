<?php

namespace Database\Factories;

use App\Models\PembayaranModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PembayaranModelFactory extends Factory
{
    protected $model = PembayaranModel::class;

    public function definition()
    {
        $metode = \App\Models\MetodeModel::factory()->create();
        $metodePembayaran = \App\Models\MetodePembayaranModel::factory()->create([
            'metode_id' => $metode->metode_id
        ]);
        return [
            'pembayaran_id' => Str::uuid()->toString(),
            'metode_pembayaran_id' => $metodePembayaran->metode_pembayaran_id,
            'status_pembayaran' => 'pending',
            'jumlah_produk' => 1,
            'total_harga' => '10000',
        ];
    }
}
