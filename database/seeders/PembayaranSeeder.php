<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PembayaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'pembayaran_id' => (string) Str::uuid(),
                'metode_pembayaran_id' => 1,
                'status_pembayaran' => 'pending',
                'jumlah_produk' => 1,
                'total_harga' => 198000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pembayaran_id' => (string) Str::uuid(),
                'metode_pembayaran_id' => 2,
                'status_pembayaran' => 'pending',
                'jumlah_produk' => 1,
                'total_harga' => 199000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pembayaran_id' => (string) Str::uuid(),
                'metode_pembayaran_id' => 3,
                'status_pembayaran' => 'pending',
                'jumlah_produk' => 1,
                'total_harga' => 239000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('t_pembayaran')->insert($data);
    }
}
