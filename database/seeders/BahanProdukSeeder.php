<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BahanProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_bahan_produk')->insert([
            ['nama_bahan' => 'Katun', 'deskripsi' => 'Nyaman dan adem.', 'created_at' => now()],
            ['nama_bahan' => 'Sifon', 'deskripsi' => 'Ringan dan jatuh.', 'created_at' => now()],
        ]);
    }
}
