<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\BannerModel;

class BannerModelFactory extends Factory
{
    protected $model = BannerModel::class;

    public function definition()
    {
        return [
            'nama_banner' => $this->faker->word,
            'foto_banner' => $this->faker->imageUrl(),
            'deskripsi' => $this->faker->sentence,
        ];
    }
}
