<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            TokoProdukSeeder::class,
            KategoriProdukSeeder::class,
            WarnaProdukSeeder::class,
            UkuranProdukSeeder::class,
            BahanProdukSeeder::class,
            ProdukSeeder::class,
            FotoProdukSeeder::class,
            DetailProdukSeeder::class,
        ]);
    }
}
