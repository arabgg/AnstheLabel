<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_kategori')->insert([
            ['nama_kategori' => 'Scarf', 'created_at' => now()],
            ['nama_kategori' => 'Pashmina', 'created_at' => now()],
            ['nama_kategori' => 'Bergo', 'created_at' => now()],
            ['nama_kategori' => 'Dress', 'created_at' => now()],
            ['nama_kategori' => 'Outer', 'created_at' => now()],
        ]);
    }
}
