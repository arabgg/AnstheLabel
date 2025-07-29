<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WarnaProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('t_warna_produk')->insert([
            [
            'warna_id' => 1,
            'produk_id' => 1,
            'created_at' => now(),
            ],
            [
            'warna_id' => 1,
            'produk_id' => 2,
            'created_at' => now(),
            ],
            [
            'warna_id' => 2,
            'produk_id' => 1,
            'created_at' => now(),
            ],
            [
            'warna_id' => 2,
            'produk_id' => 2,
            'created_at' => now(),
            ],
            [
            'warna_id' => 3,
            'produk_id' => 1,
            'created_at' => now(),
            ],
            [
            'warna_id' => 3,
            'produk_id' => 2,
            'created_at' => now(),
            ],
        ]
        );
    }
}
