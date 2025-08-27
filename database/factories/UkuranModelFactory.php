<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\UkuranModel;

class UkuranModelFactory extends Factory
{
    protected $model = UkuranModel::class;

    public function definition(): array
    {
        return [
            'nama_ukuran' => strtoupper($this->faker->randomElement(['S','M','L','XL'])),
            'deskripsi'   => $this->faker->sentence(4),
        ];
    }
}
