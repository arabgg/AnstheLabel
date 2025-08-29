<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\BahanModel;

class BahanModelFactory extends Factory
{
    protected $model = BahanModel::class;

    public function definition()
    {
        return [
            'nama_bahan' => $this->faker->word(),
            'deskripsi'  => $this->faker->sentence(6), // tambahkan ini
        ];
    }
}
