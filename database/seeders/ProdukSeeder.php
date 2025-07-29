<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('t_produk')->insert([
            [
            'kategori_id' => 1,
            'bahan_id' => 1,
            'nama_produk' => 'Hijab Syar’i',
            'deskripsi' => 'Hijab bahan adem dan jatuh.',
            'created_at' => now(),
            ],
        ]
        );
    }
}
