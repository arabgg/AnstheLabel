<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_banner')->insert([
            [
                'nama_banner' => 'Hero1.1',
                'foto_banner' => 'hero1.jpg',
                'deskripsi' => 'Foto 1 untuk hero pada tampilan awal website',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_banner' => 'Hero1.2',
                'foto_banner' => 'hero2.jpg',
                'deskripsi' => 'Foto 2 untuk hero pada tampilan awal website',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_banner' => 'Hero1.3',
                'foto_banner' => 'hero3.avif',
                'deskripsi' => 'Foto 3 untuk hero pada tampilan awal website',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_banner' => 'Hero1.4',
                'foto_banner' => 'hero4.avif',
                'deskripsi' => 'Foto 4 untuk hero pada tampilan awal website',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_banner' => 'Banner2.1',
                'foto_banner' => 'hero5.avif',
                'deskripsi' => 'Foto Banner 1 setelah new arrival',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_banner' => 'Banner2.2',
                'foto_banner' => 'hero6.avif',
                'deskripsi' => 'Foto Banner 2 setelah new arrival',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_banner' => 'Banner2.3',
                'foto_banner' => 'hero7.avif',
                'deskripsi' => 'Foto Banner 3 setelah new arrival',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_banner' => 'Banner2.4',
                'foto_banner' => 'hero8.avif',
                'deskripsi' => 'Foto Banner 4 setelah new arrival',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_banner' => 'Transaksi',
                'foto_banner' => 'transaksi.mp4',
                'deskripsi' => 'Video pada header transaksi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
