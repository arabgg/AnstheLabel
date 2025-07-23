<?php

namespace App\Http\Controllers;

use App\Models\DetailProdukModel;
use App\Models\GambarUtamaModel;
use App\Models\HeroModel;
use App\Models\KategoriModel;
use Illuminate\Http\Request;
use App\Models\ProdukModel;

class HomeController extends Controller
{
    public function index() {
        $bestproduk = [
            [
                'nama' => 'Hijab',
                'kategori' => 'Pakaian Muslim Wanita',
                'image' => 'bp01.png',
                'image_hover' => 'bp01_hover.png',
                'warna' => ['#000000', '#FFFFFF', '#FF6C6C'],
            ],
            [
                'nama' => 'Mukena',
                'kategori' => 'Pakaian Muslim Wanita',
                'image' => 'bp02.png',
                'image_hover' => 'bp02_hover.png',
                'warna' => ['#000000', '#FFFFFF', '#FF6C6C'],
            ],
            [
                'nama' => 'Dress',
                'kategori' => 'Pakaian Muslim Wanita',
                'image' => 'bp03.png',
                'image_hover' => 'bp03_hover.png',
                'warna' => ['#000000', '#FFFFFF', '#FF6C6C'],
            ],
        ];

        return view('landingpage.index', [
            'bestproduk' => $bestproduk
        ]);
    }

    public function collection(Request $request) {
        $filterKategori = $request->input('filter'); // string, satu kategori

        $kategori = Katego::all();
        $detail = DetailProdukModel::all();

        // Bangun query
        $produk = ProdukModel::with('kategori', 'detail');

        // Terapkan filter jika ada
        if (!empty($filterKategori)) {
            $produk->whereHas('kategori', function ($query) use ($filterKategori) {
                $query->where('kategori_produk_id', $filterKategori);
            });
        }

        // Eksekusi query
        $produk = $produk->get();

        $warnaList = KategoriModel::all();

        // Kirim ke view
        return view('collection.index', [
            'produk' => $produk,
            'kategori' => $kategori,
            'detail' => $detail,
            'warnaList' => $warnaList,
            'filterkategori' => $filterKategori
        ]);
    }


    public function about() {
        $produk = ProdukModel::all();

        return view('about.index', [
            'produk' => $produk
        ]);
    }
}
