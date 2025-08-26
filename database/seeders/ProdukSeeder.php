<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('t_produk')->insert([
            [
                'kategori_id' => 1,
                'bahan_id' => 1,
                'nama_produk' => 'Scarf Hijab Polos',
                'harga' => '99000',
                'diskon' => '',
                'deskripsi' => 'Scarf hijab polos berbahan ringan dan adem, memberikan kenyamanan sepanjang hari. Desain polos yang elegan cocok untuk tampilan formal maupun kasual.',
                'is_best' => 0,
                'created_at' => now(),
            ],
            [
                'kategori_id' => 1,
                'bahan_id' => 2,
                'nama_produk' => 'Scarf Hijab Motif',
                'harga' => '159000',
                'diskon' => '',
                'deskripsi' => 'Scarf hijab bermotif dengan sentuhan bahan lembut dan jatuh. Menampilkan motif elegan yang menambah kesan stylish pada setiap penampilan.',
                'is_best' => 0,
                'created_at' => now(),
            ],
            [
                'kategori_id' => 2,
                'bahan_id' => 3,
                'nama_produk' => 'Pashmina Polos',
                'harga' => '129000',
                'diskon' => '',
                'deskripsi' => 'Pashmina polos berbahan adem dan fleksibel. Cocok digunakan untuk gaya sehari-hari dengan berbagai gaya lilit yang simpel namun elegan.',
                'is_best' => 0,
                'created_at' => now(),
            ],
            [
                'kategori_id' => 2,
                'bahan_id' => 4,
                'nama_produk' => 'Pashmina Motif',
                'harga' => '199000',
                'diskon' => '',
                'deskripsi' => 'Pashmina dengan motif modern dan menarik, berbahan halus dan jatuh. Menjadikan tampilanmu lebih hidup dan fashionable.',
                'is_best' => 1,
                'created_at' => now(),
            ],
            [
                'kategori_id' => 3,
                'bahan_id' => 5,
                'nama_produk' => 'Bergo Polos',
                'harga' => '199000',
                'diskon' => '',
                'deskripsi' => 'Bergo polos berbahan adem dan ringan dengan pet yang nyaman. Praktis dipakai sehari-hari, cocok untuk tampilan simple dan rapi.',
                'is_best' => 0,
                'created_at' => now(),
            ],
            [
                'kategori_id' => 3,
                'bahan_id' => 6,
                'nama_produk' => 'Bergo Motif',
                'harga' => '239000',
                'diskon' => '',
                'deskripsi' => 'Bergo dengan motif cantik dan elegan, terbuat dari bahan yang jatuh dan nyaman dipakai. Ideal untuk tampilan santai maupun semi-formal.',
                'is_best' => 1,
                'created_at' => now(),
            ],
            [
                'kategori_id' => 4,
                'bahan_id' => 7,
                'nama_produk' => 'Sleveless Round Neck',
                'harga' => '299000',
                'diskon' => '99000',
                'deskripsi' => 'Atasan tanpa lengan dengan potongan round neck. Terbuat dari bahan adem dan fleksibel, cocok untuk cuaca hangat dan tampil kasual.',
                'is_best' => 1,
                'created_at' => now(),
            ],
            [
                'kategori_id' => 4,
                'bahan_id' => 8,
                'nama_produk' => 'Sleveless Turtle Neck',
                'harga' => '299000',
                'diskon' => '',
                'deskripsi' => 'Atasan sleeveless dengan model turtle neck yang stylish. Didesain dengan bahan lembut dan breathable, ideal untuk layering atau digunakan langsung.',
                'is_best' => 0,
                'created_at' => now(),
            ],
            [
                'kategori_id' => 5,
                'bahan_id' => 9,
                'nama_produk' => 'Kutton Strip Knitwear',
                'harga' => '349000',
                'diskon' => '149000',
                'deskripsi' => 'Knitwear rajut dengan motif garis yang modern. Menggunakan bahan berkualitas yang nyaman dipakai dan cocok untuk suasana santai maupun semi-formal.',
                'is_best' => 1,
                'created_at' => now(),
            ],
            [
                'kategori_id' => 5,
                'bahan_id' => 10,
                'nama_produk' => 'Savana Cardigan',
                'harga' => '349000',
                'diskon' => '',
                'deskripsi' => 'Cardigan dengan model longgar dan bahan lembut, cocok untuk digunakan di berbagai aktivitas. Desainnya elegan dan mudah dipadukan dengan berbagai outfit.',
                'is_best' => 1,
                'created_at' => now(),
            ],
        ]);
    }
}
