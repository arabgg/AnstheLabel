<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UkuranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_ukuran')->insert([
            ['nama_ukuran' => 'M', 'deskripsi' => 'Ukuran Medium', 'created_at' => now()],
            ['nama_ukuran' => 'L', 'deskripsi' => 'Ukuran Large', 'created_at' => now()],
        ]);
    }
}
