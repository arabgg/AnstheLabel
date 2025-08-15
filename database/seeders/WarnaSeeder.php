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
            ['kode_hex' => '#000000', 'nama_warna' => 'Hitam', 'created_at' => now()],
            ['kode_hex' => '#FFFFFF', 'nama_warna' => 'Putih', 'created_at' => now()],
            ['kode_hex' => '#F5F5F5', 'nama_warna' => 'Putih Gading', 'created_at' => now()],
            ['kode_hex' => '#808080', 'nama_warna' => 'Abu-Abu', 'created_at' => now()],
            ['kode_hex' => '#C0C0C0', 'nama_warna' => 'Silver', 'created_at' => now()],
            ['kode_hex' => '#A52A2A', 'nama_warna' => 'Cokelat Tua', 'created_at' => now()],
            ['kode_hex' => '#D2B48C', 'nama_warna' => 'Tan', 'created_at' => now()],
            ['kode_hex' => '#BDB76B', 'nama_warna' => 'Khaki', 'created_at' => now()],
            ['kode_hex' => '#F0E68C', 'nama_warna' => 'Light Khaki', 'created_at' => now()],
            ['kode_hex' => '#FFE4C4', 'nama_warna' => 'Beige', 'created_at' => now()],

            ['kode_hex' => '#FFDAB9', 'nama_warna' => 'Peach', 'created_at' => now()],
            ['kode_hex' => '#FFB6C1', 'nama_warna' => 'Light Pink', 'created_at' => now()],
            ['kode_hex' => '#FFC0CB', 'nama_warna' => 'Pink', 'created_at' => now()],
            ['kode_hex' => '#FF69B4', 'nama_warna' => 'Hot Pink', 'created_at' => now()],
            ['kode_hex' => '#C71585', 'nama_warna' => 'Magenta', 'created_at' => now()],

            ['kode_hex' => '#DC143C', 'nama_warna' => 'Crimson', 'created_at' => now()],
            ['kode_hex' => '#B22222', 'nama_warna' => 'Firebrick', 'created_at' => now()],
            ['kode_hex' => '#FF4500', 'nama_warna' => 'Orange Red', 'created_at' => now()],
            ['kode_hex' => '#FFA500', 'nama_warna' => 'Orange', 'created_at' => now()],
            ['kode_hex' => '#FFD700', 'nama_warna' => 'Gold', 'created_at' => now()],

            ['kode_hex' => '#F0E68C', 'nama_warna' => 'Kuning Muda', 'created_at' => now()],
            ['kode_hex' => '#FFFFE0', 'nama_warna' => 'Lemon Chiffon', 'created_at' => now()],
            ['kode_hex' => '#ADFF2F', 'nama_warna' => 'Green Yellow', 'created_at' => now()],
            ['kode_hex' => '#9ACD32', 'nama_warna' => 'Yellow Green', 'created_at' => now()],
            ['kode_hex' => '#2E8B57', 'nama_warna' => 'Sea Green', 'created_at' => now()],

            ['kode_hex' => '#228B22', 'nama_warna' => 'Forest Green', 'created_at' => now()],
            ['kode_hex' => '#32CD32', 'nama_warna' => 'Lime Green', 'created_at' => now()],
            ['kode_hex' => '#90EE90', 'nama_warna' => 'Light Green', 'created_at' => now()],
            ['kode_hex' => '#00CED1', 'nama_warna' => 'Dark Turquoise', 'created_at' => now()],
            ['kode_hex' => '#40E0D0', 'nama_warna' => 'Turquoise', 'created_at' => now()],

            ['kode_hex' => '#87CEFA', 'nama_warna' => 'Light Sky Blue', 'created_at' => now()],
            ['kode_hex' => '#4682B4', 'nama_warna' => 'Steel Blue', 'created_at' => now()],
            ['kode_hex' => '#4169E1', 'nama_warna' => 'Royal Blue', 'created_at' => now()],
            ['kode_hex' => '#0000FF', 'nama_warna' => 'Blue', 'created_at' => now()],
            ['kode_hex' => '#6A5ACD', 'nama_warna' => 'Slate Blue', 'created_at' => now()],

            ['kode_hex' => '#8A2BE2', 'nama_warna' => 'Blue Violet', 'created_at' => now()],
            ['kode_hex' => '#9932CC', 'nama_warna' => 'Dark Orchid', 'created_at' => now()],
            ['kode_hex' => '#800080', 'nama_warna' => 'Purple', 'created_at' => now()],
            ['kode_hex' => '#4B0082', 'nama_warna' => 'Indigo', 'created_at' => now()],
            ['kode_hex' => '#EE82EE', 'nama_warna' => 'Violet', 'created_at' => now()],
        ]);
    }
}