<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShowProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('t_show_produk')->insert([
            [
            'produk_id' => 1,
            'warna_produk_id' => 1,
            'detail_produk_id' => 1,
            'detail_produk_id' => 1,
            'foto_produk' => 'foto_hijab1.jpg',
            'status_foto' => 1,
            'created_at' => now(),
            ],
            [
            'produk_id' => 1,
            'warna_produk_id' => 1,
            'detail_produk_id' => 1,
            'foto_produk' => 'foto_hijab1.jpg',
            'status_foto' => 0,
            'created_at' => now(),
            ],
            [
            'produk_id' => 1,
            'warna_produk_id' => 1,
            'detail_produk_id' => 1,
            'foto_produk' => 'foto_hijab1.jpg',
            'status_foto' => 0,
            'created_at' => now(),
            ],
            [
            'produk_id' => 1,
            'warna_produk_id' => 1,
            'detail_produk_id' => 1,
            'foto_produk' => 'foto_hijab1.jpg',
            'status_foto' => 0,
            'created_at' => now(),
            ],
        ]
        );
    }
}
