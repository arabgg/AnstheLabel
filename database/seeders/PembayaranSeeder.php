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
                'pembayaran_id' => 'd7d9dfaa-9eca-415f-82c2-c4b388c7960f',
                'metode_pembayaran_id' => 1,
                'status_pembayaran' => 'Menunggu Pembayaran',
                'jumlah_produk' => 1,
                'total_harga' => 198000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pembayaran_id' => '0bdebcaa-0353-44ab-861c-2992c13d45fc',
                'metode_pembayaran_id' => 2,
                'status_pembayaran' => 'Menunggu Pembayaran',
                'jumlah_produk' => 1,
                'total_harga' => 199000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pembayaran_id' => '01529401-c821-4659-99b1-2f3d3502fdfd',
                'metode_pembayaran_id' => 3,
                'status_pembayaran' => 'Menunggu Pembayaran',
                'jumlah_produk' => 1,
                'total_harga' => 239000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('t_pembayaran')->insert($data);
    }
}
