<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UkuranProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('t_ukuran_produk')->insert([
            [
            'produk_id' => 1,
            'ukuran_id' => 1,
            'created_at' => now(),
            ],
            [
            'produk_id' => 1,
            'ukuran_id' => 2,
            'created_at' => now(),
            ],
            [
            'produk_id' => 2,
            'ukuran_id' => 1,
            'created_at' => now(),
            ],
            [
            'produk_id' => 2,
            'ukuran_id' => 2,
            'created_at' => now(),
            ],
            [
            'produk_id' => 3,
            'ukuran_id' => 1,
            'created_at' => now(),
            ],
            [
            'produk_id' => 3,
            'ukuran_id' => 2,
            'created_at' => now(),
            ],
        ]
        );
    }
}
