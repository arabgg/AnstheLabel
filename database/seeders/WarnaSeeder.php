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
            ['kode_hex' => '#000000', 'created_at' => now()], // Hitam
            ['kode_hex' => '#FFFFFF', 'created_at' => now()], // Putih
            ['kode_hex' => '#F5F5F5', 'created_at' => now()], // Putih gading
            ['kode_hex' => '#808080', 'created_at' => now()], // Abu-abu
            ['kode_hex' => '#C0C0C0', 'created_at' => now()], // Silver
            ['kode_hex' => '#A52A2A', 'created_at' => now()], // Cokelat tua
            ['kode_hex' => '#D2B48C', 'created_at' => now()], // Tan
            ['kode_hex' => '#BDB76B', 'created_at' => now()], // Khaki
            ['kode_hex' => '#F0E68C', 'created_at' => now()], // Light Khaki
            ['kode_hex' => '#FFE4C4', 'created_at' => now()], // Beige

            ['kode_hex' => '#FFDAB9', 'created_at' => now()], // Peach
            ['kode_hex' => '#FFB6C1', 'created_at' => now()], // Light Pink
            ['kode_hex' => '#FFC0CB', 'created_at' => now()], // Pink
            ['kode_hex' => '#FF69B4', 'created_at' => now()], // Hot Pink
            ['kode_hex' => '#C71585', 'created_at' => now()], // Magenta

            ['kode_hex' => '#DC143C', 'created_at' => now()], // Crimson
            ['kode_hex' => '#B22222', 'created_at' => now()], // Firebrick
            ['kode_hex' => '#FF4500', 'created_at' => now()], // Orange Red
            ['kode_hex' => '#FFA500', 'created_at' => now()], // Orange
            ['kode_hex' => '#FFD700', 'created_at' => now()], // Gold

            ['kode_hex' => '#F0E68C', 'created_at' => now()], // Kuning muda
            ['kode_hex' => '#FFFFE0', 'created_at' => now()], // Lemon chiffon
            ['kode_hex' => '#ADFF2F', 'created_at' => now()], // Green yellow
            ['kode_hex' => '#9ACD32', 'created_at' => now()], // Yellow green
            ['kode_hex' => '#2E8B57', 'created_at' => now()], // Sea green

            ['kode_hex' => '#228B22', 'created_at' => now()], // Forest green
            ['kode_hex' => '#32CD32', 'created_at' => now()], // Lime green
            ['kode_hex' => '#90EE90', 'created_at' => now()], // Light green
            ['kode_hex' => '#00CED1', 'created_at' => now()], // Dark turquoise
            ['kode_hex' => '#40E0D0', 'created_at' => now()], // Turquoise

            ['kode_hex' => '#87CEFA', 'created_at' => now()], // Light sky blue
            ['kode_hex' => '#4682B4', 'created_at' => now()], // Steel blue
            ['kode_hex' => '#4169E1', 'created_at' => now()], // Royal blue
            ['kode_hex' => '#0000FF', 'created_at' => now()], // Blue
            ['kode_hex' => '#6A5ACD', 'created_at' => now()], // Slate blue

            ['kode_hex' => '#8A2BE2', 'created_at' => now()], // Blue violet
            ['kode_hex' => '#9932CC', 'created_at' => now()], // Dark orchid
            ['kode_hex' => '#800080', 'created_at' => now()], // Purple
            ['kode_hex' => '#4B0082', 'created_at' => now()], // Indigo
            ['kode_hex' => '#EE82EE', 'created_at' => now()], // Violet
        ]);
    }
}