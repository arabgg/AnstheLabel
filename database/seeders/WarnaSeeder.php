<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WarnaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_warna')->insert([
            ['nama_warna' => 'Hitam', 'created_at' => now()],
            ['nama_warna' => 'Putih', 'created_at' => now()],
        ]);
    }
}
