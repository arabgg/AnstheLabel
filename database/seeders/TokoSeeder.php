<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TokoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_toko')->insert([
            [
            'nama_toko' => 'Shopee',
            'icon_toko' => 'shopee.png',
            'created_at' => now(),
            ],
            [
            'nama_toko' => 'Tokopedia',
            'icon_toko' => 'tokped.png',
            'created_at' => now(),
            ],
        ]);
    }
}
