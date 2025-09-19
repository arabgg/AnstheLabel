<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MetodeModelFactory extends Factory
{
    protected $model = \App\Models\MetodeModel::class;

    public function definition()
    {
        return [
            'nama_metode' => $this->faker->word . ' Method',
        ];
    }
}
