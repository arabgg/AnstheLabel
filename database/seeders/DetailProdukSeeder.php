<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DetailProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('t_detail_produk')->insert([
            [
            'bahan_produk_id' => 1,
            'ukuran_produk_id' => 1,
            'created_at' => now(),
            ],
            [
            'bahan_produk_id' => 1,
            'ukuran_produk_id' => 1,
            'created_at' => now(),
            ],
            [
            'bahan_produk_id' => 1,
            'ukuran_produk_id' => 1,
            'created_at' => now(),
            ],
            [
            'bahan_produk_id' => 1,
            'ukuran_produk_id' => 1,
            'created_at' => now(),
            ],
            ]
        );
    }
}
