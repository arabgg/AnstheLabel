<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\WarnaModel;

class WarnaModelFactory extends Factory
{
    protected $model = WarnaModel::class;

    public function definition(): array
    {
        $hex = strtoupper($this->faker->safeHexColor());
        return [
            'nama_warna' => $hex,
            'kode_hex'   => $hex,
        ];
    }
}
