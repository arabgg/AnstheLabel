<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MetodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_metode_pembayaran')->insert([
            ['nama_metode' => 'Transfer Bank', 'kode_bayar' => '12345', 'created_at' => now()],
            ['nama_metode' => 'Auto Bank', 'kode_bayar' => '678910', 'created_at' => now()],
        ]);
    }
}
