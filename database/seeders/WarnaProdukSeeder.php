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
        DB::table('m_warna_produk')->insert([
            ['nama_warna' => 'Hitam', 'kode_hex' => '#000000', 'created_at' => now()],
            ['nama_warna' => 'Putih', 'kode_hex' => '#FFFFFF', 'created_at' => now()],
        ]);
    }
}
