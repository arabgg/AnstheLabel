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
            TokoSeeder::class,
            KategoriSeeder::class,
            WarnaSeeder::class,
            UkuranSeeder::class,
            BahanSeeder::class,
            ProdukSeeder::class,
            UkuranProdukSeeder::class,
            UkuranProdukSeeder::class,
            FotoProdukSeeder::class,
            TokoProdukSeeder::class,
        ]);
    }
}
