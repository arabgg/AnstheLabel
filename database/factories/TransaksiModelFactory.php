<?php

namespace Database\Factories;

use App\Models\TransaksiModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TransaksiModelFactory extends Factory
{
    protected $model = TransaksiModel::class;

    public function definition()
    {
        $pembayaran = \App\Models\PembayaranModel::factory()->create();
        return [
            'transaksi_id' => Str::uuid()->toString(),
            'pembayaran_id' => $pembayaran->pembayaran_id,
            'kode_invoice' => 'INV' . $this->faker->unique()->numerify('####'),
            'nama_customer' => $this->faker->name(),
            'no_telp' => $this->faker->phoneNumber(),
            'email' => $this->faker->safeEmail(),
            'alamat' => $this->faker->address(),
            // Pilih salah satu value enum yang valid
            'status_transaksi' => $this->faker->randomElement([
                'menunggu pembayaran', 'dikemas', 'dikirim', 'selesai', 'batal'
            ]),
        ];
    }
}
