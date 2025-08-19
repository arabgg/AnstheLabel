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
            ['kode_hex' => '#2c7851ff', 'created_at' => now()],
            ['kode_hex' => '#8d5858ff', 'created_at' => now()],
            ['kode_hex' => '#ca8888ff', 'created_at' => now()],
            ['kode_hex' => '#936c6cff', 'created_at' => now()],
        ]);
    }
}
