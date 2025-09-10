<?php

namespace Database\Factories;

use App\Models\ProdukModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProdukModelFactory extends Factory
{
    protected $model = ProdukModel::class;

    public function definition()
    {
        return [
            'nama_produk' => $this->faker->words(3, true),
            'deskripsi'   => $this->faker->sentence(),
            'harga'       => $this->faker->numberBetween(50000, 200000),
            'diskon'      => $this->faker->randomElement([0, 10000, 20000]),
            'stok_produk' => $this->faker->numberBetween(0, 100),
            'kategori_id' => \App\Models\KategoriModel::factory(),
            'bahan_id'    => \App\Models\BahanModel::factory(),
        ];
    }
}