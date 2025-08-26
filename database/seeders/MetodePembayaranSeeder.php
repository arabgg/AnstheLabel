<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MetodePembayaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('t_metode_pembayaran')->insert([
            ['metode_id' => 1, 'nama_pembayaran' => 'bca', 'kode_bayar' => '123456789', 'icon' => 'bca.png', 'created_at' => now()],
            ['metode_id' => 1, 'nama_pembayaran' => 'bri', 'kode_bayar' => '987654321', 'icon' => 'bri.png', 'created_at' => now()],
            ['metode_id' => 2, 'nama_pembayaran' => 'dana', 'kode_bayar' => '749274658', 'icon' => 'dana.png', 'created_at' => now()],
            ['metode_id' => 2, 'nama_pembayaran' => 'ovo', 'kode_bayar' => '759202747', 'icon' => 'ovo.png', 'created_at' => now()],
            ['metode_id' => 3, 'nama_pembayaran' => 'qris', 'kode_bayar' => 'barcode.png', 'icon' => 'qris.png', 'created_at' => now()],
        ]);
    }
}
