<?php

namespace Database\Factories;

use App\Models\MetodePembayaranModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class MetodePembayaranModelFactory extends Factory
{
    protected $model = MetodePembayaranModel::class;

    public function definition()
    {
        return [
            'metode_id' => 1, // Pastikan id ini ada di tabel m_metode_pembayaran
            'nama_pembayaran' => $this->faker->company . ' Payment',
            'kode_bayar' => $this->faker->unique()->bothify('KODE-####'),
            'status_pembayaran' => $this->faker->boolean,
            'icon' => $this->faker->optional()->imageUrl(),
        ];
    }
}
