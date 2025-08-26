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
            ['nama_metode' => 'Transfer Bank', 'created_at' => now()],
            ['nama_metode' => 'E-Wallet', 'created_at' => now()],
            ['nama_metode' => 'Auto Bank', 'created_at' => now()],
        ]);
    }
}
