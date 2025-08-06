<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TokoProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('t_toko_produk')->insert([
            [
            'produk_id' => 1,
            'toko_id' => 1,
            'url_toko' => 'https://shopee.co.id/',
            'created_at' => now(),
            ],
            [
            'produk_id' => 1,
            'toko_id' => 2,
            'url_toko' => 'https://www.tokopedia.com/',
            'created_at' => now(),
            ],
            [
            'produk_id' => 2,
            'toko_id' => 1,
            'url_toko' => 'https://shopee.co.id/',
            'created_at' => now(),
            ],
            [
            'produk_id' => 2,
            'toko_id' => 2,
            'url_toko' => 'https://www.tokopedia.com/',
            'created_at' => now(),
            ],
            [
            'produk_id' => 3,
            'toko_id' => 1,
            'url_toko' => 'https://shopee.co.id/',
            'created_at' => now(),
            ],
            [
            'produk_id' => 3,
            'toko_id' => 2,
            'url_toko' => 'https://www.tokopedia.com/',
            'created_at' => now(),
            ],
            [
            'produk_id' => 4,
            'toko_id' => 1,
            'url_toko' => 'https://shopee.co.id/',
            'created_at' => now(),
            ],
            [
            'produk_id' => 4,
            'toko_id' => 2,
            'url_toko' => 'https://www.tokopedia.com/',
            'created_at' => now(),
            ],
            [
            'produk_id' => 5,
            'toko_id' => 1,
            'url_toko' => 'https://shopee.co.id/',
            'created_at' => now(),
            ],
            [
            'produk_id' => 5,
            'toko_id' => 2,
            'url_toko' => 'https://www.tokopedia.com/',
            'created_at' => now(),
            ],
            [
            'produk_id' => 6,
            'toko_id' => 1,
            'url_toko' => 'https://shopee.co.id/',
            'created_at' => now(),
            ],
            [
            'produk_id' => 6,
            'toko_id' => 2,
            'url_toko' => 'https://www.tokopedia.com/',
            'created_at' => now(),
            ],
            [
            'produk_id' => 7,
            'toko_id' => 1,
            'url_toko' => 'https://shopee.co.id/',
            'created_at' => now(),
            ],
            [
            'produk_id' => 7,
            'toko_id' => 2,
            'url_toko' => 'https://www.tokopedia.com/',
            'created_at' => now(),
            ],
            [
            'produk_id' => 8,
            'toko_id' => 1,
            'url_toko' => 'https://shopee.co.id/',
            'created_at' => now(),
            ],
            [
            'produk_id' => 8,
            'toko_id' => 2,
            'url_toko' => 'https://www.tokopedia.com/',
            'created_at' => now(),
            ],
            [
            'produk_id' => 9,
            'toko_id' => 1,
            'url_toko' => 'https://shopee.co.id/',
            'created_at' => now(),
            ],
            [
            'produk_id' => 9,
            'toko_id' => 2,
            'url_toko' => 'https://www.tokopedia.com/',
            'created_at' => now(),
            ],
            [
            'produk_id' => 10,
            'toko_id' => 1,
            'url_toko' => 'https://shopee.co.id/',
            'created_at' => now(),
            ],
            [
            'produk_id' => 10,
            'toko_id' => 2,
            'url_toko' => 'https://www.tokopedia.com/',
            'created_at' => now(),
            ],
            [
            'produk_id' =>10,
            'toko_id' => 3,
            'url_toko' => 'https://www.tiktok.com/',
            'created_at' => now(),
            ],
        ]
        );
    }
}
