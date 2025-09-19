<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('t_transaksi')->insert([
            [
                'transaksi_id'     => '252a861b-6061-436a-9bc9-07e8c392954b',
                'pembayaran_id'    => 'd7d9dfaa-9eca-415f-82c2-c4b388c7960f',
                'kode_invoice'     => 'ANS-20250829-252a861b',
                'nama_customer'    => 'Aezakmi',
                'no_telp'          => '12121212121',
                'email'            => 'adadadadada@mail.com',
                'alamat'           => 'SSDsd, 3232e2e2e2e, dawdawdwa',
                'status_transaksi' => 'selesai',
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'transaksi_id'     => 'd0e8b37b-74c0-4fdf-8ca4-cdd7d633dea1',
                'pembayaran_id'    => '0bdebcaa-0353-44ab-861c-2992c13d45fc',
                'kode_invoice'     => 'ANS-20250829-d0e8b37b',
                'nama_customer'    => 'testtransaksi',
                'no_telp'          => '0813356782',
                'email'            => 'testtransaksi@gmail.com',
                'alamat'           => 'Jl. Bandulan IX/no. 574b, Kota Malang, Sukun',
                'status_transaksi' => 'menunggu pembayaran',
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'transaksi_id'     => 'd4de5e3d-72eb-4bd1-804c-a580a7ce6944',
                'pembayaran_id'    => '01529401-c821-4659-99b1-2f3d3502fdfd',
                'kode_invoice'     => 'ANS-20250829-d4de5e3d',
                'nama_customer'    => 'coba2',
                'no_telp'          => '0812323232',
                'email'            => 'cobasaja@gmail.com',
                'alamat'           => 'Jl. Bandulan IX/no. 574b, Kota Malang, Sukun',
                'status_transaksi' => 'menunggu pembayaran',
                'created_at'       => now(),
                'updated_at'       => now(),
            ]
        ]);
    }
}
