<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\KategoriModel;

class KategoriModelFactory extends Factory
{
    protected $model = KategoriModel::class;

    public function definition(): array
    {
        return [
            'nama_kategori' => $this->faker->word(),
        ];
    }
}
