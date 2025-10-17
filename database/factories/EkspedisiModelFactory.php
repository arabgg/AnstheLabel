<?php

namespace Database\Factories;

use App\Models\EkspedisiModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EkspedisiModel>
 */
class EkspedisiModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EkspedisiModel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_ekspedisi' => fake()->company(),
            'status_ekspedisi' => fake()->boolean(),
            'icon' => fake()->word() . '.png',
        ];
    }
}
