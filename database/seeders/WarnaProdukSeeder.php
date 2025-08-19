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
            ['produk_id' => 1, 'warna_id' => 8,  'created_at' => now()],
            ['produk_id' => 1, 'warna_id' => 2,  'created_at' => now()],
            ['produk_id' => 1, 'warna_id' => 18, 'created_at' => now()],
            ['produk_id' => 1, 'warna_id' => 16, 'created_at' => now()],
            ['produk_id' => 1, 'warna_id' => 15, 'created_at' => now()],

            ['produk_id' => 2, 'warna_id' => 6,  'created_at' => now()],
            ['produk_id' => 2, 'warna_id' => 29, 'created_at' => now()],
            ['produk_id' => 2, 'warna_id' => 5,  'created_at' => now()],
            ['produk_id' => 2, 'warna_id' => 27, 'created_at' => now()],
            ['produk_id' => 2, 'warna_id' => 34, 'created_at' => now()],

            ['produk_id' => 3, 'warna_id' => 24, 'created_at' => now()],
            ['produk_id' => 3, 'warna_id' => 1,  'created_at' => now()],
            ['produk_id' => 3, 'warna_id' => 26, 'created_at' => now()],
            ['produk_id' => 3, 'warna_id' => 32, 'created_at' => now()],
            ['produk_id' => 3, 'warna_id' => 30, 'created_at' => now()],

            ['produk_id' => 4, 'warna_id' => 13, 'created_at' => now()],
            ['produk_id' => 4, 'warna_id' => 12, 'created_at' => now()],
            ['produk_id' => 4, 'warna_id' => 7,  'created_at' => now()],
            ['produk_id' => 4, 'warna_id' => 25, 'created_at' => now()],
            ['produk_id' => 4, 'warna_id' => 4,  'created_at' => now()],

            ['produk_id' => 5, 'warna_id' => 14, 'created_at' => now()],
            ['produk_id' => 5, 'warna_id' => 10, 'created_at' => now()],
            ['produk_id' => 5, 'warna_id' => 20, 'created_at' => now()],
            ['produk_id' => 5, 'warna_id' => 17, 'created_at' => now()],
            ['produk_id' => 5, 'warna_id' => 21, 'created_at' => now()],

            ['produk_id' => 6, 'warna_id' => 22, 'created_at' => now()],
            ['produk_id' => 6, 'warna_id' => 19, 'created_at' => now()],
            ['produk_id' => 6, 'warna_id' => 11, 'created_at' => now()],
            ['produk_id' => 6, 'warna_id' => 3,  'created_at' => now()],
            ['produk_id' => 6, 'warna_id' => 33, 'created_at' => now()],

            ['produk_id' => 7, 'warna_id' => 31, 'created_at' => now()],
            ['produk_id' => 7, 'warna_id' => 9,  'created_at' => now()],
            ['produk_id' => 7, 'warna_id' => 28, 'created_at' => now()],
            ['produk_id' => 7, 'warna_id' => 23, 'created_at' => now()],
            ['produk_id' => 7, 'warna_id' => 35, 'created_at' => now()], // Jika tersedia

            ['produk_id' => 8, 'warna_id' => 34, 'created_at' => now()],
            ['produk_id' => 8, 'warna_id' => 16, 'created_at' => now()],
            ['produk_id' => 8, 'warna_id' => 5,  'created_at' => now()],
            ['produk_id' => 8, 'warna_id' => 18, 'created_at' => now()],
            ['produk_id' => 8, 'warna_id' => 7,  'created_at' => now()],

            ['produk_id' => 9, 'warna_id' => 6,  'created_at' => now()],
            ['produk_id' => 9, 'warna_id' => 14, 'created_at' => now()],
            ['produk_id' => 9, 'warna_id' => 22, 'created_at' => now()],
            ['produk_id' => 9, 'warna_id' => 29, 'created_at' => now()],
            ['produk_id' => 9, 'warna_id' => 26, 'created_at' => now()],

            ['produk_id' => 10, 'warna_id' => 2,  'created_at' => now()],
            ['produk_id' => 10, 'warna_id' => 10, 'created_at' => now()],
            ['produk_id' => 10, 'warna_id' => 20, 'created_at' => now()],
            ['produk_id' => 10, 'warna_id' => 17, 'created_at' => now()],
            ['produk_id' => 10, 'warna_id' => 8,  'created_at' => now()],
        ]);
    }
}
