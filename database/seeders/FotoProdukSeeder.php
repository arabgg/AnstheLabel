<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FotoProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('t_foto_produk')->insert([
            'produk_id' => 1,
            'foto_produk' => 'foto_hijab1.jpg',
            'status_foto' => 1,
            'created_at' => now(),
        ]);
    }
}
