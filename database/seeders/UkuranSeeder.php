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
            // Ukuran Dress
            ['nama_ukuran' => 'S', 'deskripsi' => 'Ukuran Small (Dress)', 'created_at' => now()],
            ['nama_ukuran' => 'M', 'deskripsi' => 'Ukuran Medium (Dress)', 'created_at' => now()],
            ['nama_ukuran' => 'L', 'deskripsi' => 'Ukuran Large (Dress)', 'created_at' => now()],
            ['nama_ukuran' => 'XL', 'deskripsi' => 'Ukuran Extra Large (Dress)', 'created_at' => now()],
            ['nama_ukuran' => 'XXL', 'deskripsi' => 'Ukuran Double Extra Large (Dress)', 'created_at' => now()],

            // All Size untuk Hijab
            ['nama_ukuran' => 'All Size - Pashmina', 'deskripsi' => 'Ukuran umum pashmina ±175cm x 75cm', 'created_at' => now()],
            ['nama_ukuran' => 'All Size - Segi Empat', 'deskripsi' => 'Ukuran umum segi empat ±110cm x 110cm', 'created_at' => now()],
            ['nama_ukuran' => 'All Size - Bergo', 'deskripsi' => 'Ukuran bebas dengan penyesuaian elastis (fit to all)', 'created_at' => now()],
        ]);
    }
}
