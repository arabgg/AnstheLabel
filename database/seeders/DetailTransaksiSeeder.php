<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DetailTransaksiSeeder extends Seeder
{
    public function run()
    {
        DB::table('t_detail_transaksi')->insert([
            [
                'detail_transaksi_id' => '50ea7b2b-130e-4b5c-8dda-c1240470eaf4',
                'transaksi_id' => '252a861b-6061-436a-9bc9-07e8c392954b',
                'pembayaran_id' => 'd7d9dfaa-9eca-415f-82c2-c4b388c7960f',
                'produk_id' => 5,
                'ukuran_id' => 8,
                'warna_id' => 21,
                'jumlah' => 1,
                'created_at' => '2025-08-29 06:42:09',
                'updated_at' => '2025-08-29 06:42:09',
            ],
            [
                'detail_transaksi_id' => '76e42c97-61cb-4508-9e86-ff4c6782f484',
                'transaksi_id' => 'd0e8b37b-74c0-4fdf-8ca4-cdd7d633dea1',
                'pembayaran_id' => '0bdebcaa-0353-44ab-861c-2992c13d45fc',
                'produk_id' => 4,
                'ukuran_id' => 6,
                'warna_id' => 4,
                'jumlah' => 1,
                'created_at' => '2025-08-29 06:56:18',
                'updated_at' => '2025-08-29 06:56:18',
            ],
            [
                'detail_transaksi_id' => 'c80c9b40-4e9f-40df-afa5-5728db49e82b',
                'transaksi_id' => 'd4de5e3d-72eb-4bd1-804c-a580a7ce6944',
                'pembayaran_id' => '01529401-c821-4659-99b1-2f3d3502fdfd',
                'produk_id' => 4,
                'ukuran_id' => 6,
                'warna_id' => 13,
                'jumlah' => 1,
                'created_at' => '2025-08-29 06:42:49',
                'updated_at' => '2025-08-29 06:42:49',
            ],
        ]);
    }
}
