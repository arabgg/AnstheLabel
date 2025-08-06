<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BahanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_bahan')->insert([
            ['nama_bahan' => 'Katun', 'deskripsi' => 'Bahan lembut, adem, dan menyerap keringat. Cocok untuk penggunaan harian.', 'created_at' => now()],
            ['nama_bahan' => 'Sifon', 'deskripsi' => 'Bahan ringan, jatuh, dan semi-transparan. Cocok untuk tampilan anggun dan formal.', 'created_at' => now()],
            ['nama_bahan' => 'Jersey', 'deskripsi' => 'Bahan elastis, tidak mudah kusut, dan nyaman dipakai. Cocok untuk pashmina dan dress kasual.', 'created_at' => now()],
            ['nama_bahan' => 'Voal', 'deskripsi' => 'Bahan tipis, ringan, tidak licin, dan mudah dibentuk. Favorit untuk hijab segi empat.', 'created_at' => now()],
            ['nama_bahan' => 'Silk Satin', 'deskripsi' => 'Bahan halus, mengilap, dan mewah. Cocok untuk acara formal.', 'created_at' => now()],
            ['nama_bahan' => 'Crepe', 'deskripsi' => 'Bahan bertekstur halus, flowy, dan tidak menerawang. Nyaman digunakan seharian.', 'created_at' => now()],
            ['nama_bahan' => 'Linen', 'deskripsi' => 'Bahan ringan dan breathable, memberikan kesan natural dan estetik.', 'created_at' => now()],
            ['nama_bahan' => 'Rajut', 'deskripsi' => 'Bahan tebal, elastis, dan hangat. Cocok untuk cardigan dan outerwear.', 'created_at' => now()],
            ['nama_bahan' => 'Wolpeach', 'deskripsi' => 'Bahan mirip wool tapi lebih ringan, tidak panas, dan tidak menerawang.', 'created_at' => now()],
            ['nama_bahan' => 'Maxmara', 'deskripsi' => 'Bahan licin, ringan, dan mewah dengan kilau elegan. Cocok untuk dress dan gamis.', 'created_at' => now()],
        ]);
    }
}
