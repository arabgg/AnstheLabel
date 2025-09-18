<?php

namespace Database\Factories;

use App\Models\MetodeModel;
use App\Models\MetodePembayaranModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class MetodePembayaranModelFactory extends Factory
{
    protected $model = MetodePembayaranModel::class;

    public function definition()
    {
        return [
            // relasi otomatis → kalau nggak dikasih metode_id, dia akan buat MetodeModel baru
            'metode_id' => MetodeModel::factory(),

            'nama_pembayaran' => $this->faker->company . ' Payment',
            'kode_bayar' => $this->faker->unique()->bothify('KODE-####'),
            'atas_nama' => $this->faker->name,
            'status_pembayaran' => $this->faker->boolean,
            'icon' => null, // biar konsisten dengan validasi controller
        ];
    }
}
