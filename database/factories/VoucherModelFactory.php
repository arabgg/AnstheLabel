<?php

namespace Database\Factories;

use App\Models\VoucherModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VoucherModel>
 */
class VoucherModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = VoucherModel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-1 month', '+1 month');
        $endDate = fake()->dateTimeBetween($startDate, '+6 months');

        return [
            'kode_voucher' => strtoupper(fake()->unique()->lexify('VOUCHER???')),
            'deskripsi' => fake()->sentence(),
            'tipe_diskon' => fake()->randomElement(['persen', 'nominal']),
            'nilai_diskon' => fake()->numberBetween(5, 50),
            'min_transaksi' => fake()->numberBetween(10000, 100000),
            'usage_limit' => fake()->numberBetween(1, 100),
            'used' => fake()->numberBetween(0, 10),
            'status_voucher' => fake()->boolean(),
            'tanggal_mulai' => $startDate,
            'tanggal_berakhir' => $endDate,
        ];
    }
}
