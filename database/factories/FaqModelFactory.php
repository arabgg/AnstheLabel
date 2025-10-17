<?php

namespace Database\Factories;

use App\Models\FaqModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FaqModel>
 */
class FaqModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FaqModel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pertanyaan' => fake()->sentence(),
            'jawaban' => fake()->paragraph(),
        ];
    }
}
