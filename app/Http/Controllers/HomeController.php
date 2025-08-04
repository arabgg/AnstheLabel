<?php

namespace App\Http\Controllers;

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
                'harga' => 'Rp. 259.000',
                'image_hover' => 'bp01_hover.png',
                'warna' => ['#000000', '#FFFFFF', '#FF6C6C'],
            ],
            [
                'nama' => 'Dress',
                'kategori' => 'Pakaian Muslim Wanita',
                'image' => 'bp02.png',
                'harga' => 'Rp. 399.000',
                'image_hover' => 'bp02_hover.png',
                'warna' => ['#000000', '#FFFFFF', '#FF6C6C'],
            ],
            [
                'nama' => 'Mukena',
                'kategori' => 'Pakaian Muslim Wanita',
                'image' => 'bp03.png',
                'harga' => 'Rp. 259.000',
                'harga_diskon' => 'Rp. 199.000',
                'image_hover' => 'bp03_hover.png',
                'warna' => ['#000000', '#FFFFFF', '#FF6C6C'],
            ],
            [
                'nama' => 'Mukena',
                'kategori' => 'Pakaian Muslim Wanita',
                'image' => 'bp03.png',
                'harga' => 'Rp. 259.000',
                'harga_diskon' => 'Rp. 199.000',
                'image_hover' => 'bp03_hover.png',
                'warna' => ['#000000', '#FFFFFF', '#FF6C6C'],
            ],
        ];

        $viscose = [
            [
                'nama' => 'Hijab',
                'kategori' => 'Pakaian Muslim Wanita',
                'image' => 'bp01.png',
                'image_hover' => 'bp01_hover.png',
                'warna' => ['#000000', '#FF6C6C', '#2c7851ff', '#8d5858ff', '#ca8888ff', '#936c6cff', '#FFFFFF',],
            ],
            [
                'nama' => 'Hijab',
                'kategori' => 'Pakaian Muslim Wanita',
                'image' => 'bp01.png',
                'image_hover' => 'bp01_hover.png',
                'warna' => ['#000000', '#8d5858ff', '#ca8888ff', '#FF6C6C', '#2c7851ff', '#936c6cff', '#FFFFFF',],
            ],
            [
                'nama' => 'Hijab',
                'kategori' => 'Pakaian Muslim Wanita',
                'image' => 'bp01.png',
                'image_hover' => 'bp01_hover.png',
                'warna' => ['#FF6C6C', '#2c7851ff', '#8d5858ff', '#ca8888ff', '#936c6cff', '#000000', '#FFFFFF'],
            ],
            [
                'nama' => 'Hijab',
                'kategori' => 'Pakaian Muslim Wanita',
                'image' => 'bp01.png',
                'image_hover' => 'bp01_hover.png',
                'warna' => ['#000000', '#FF6C6C', '#8d5858ff', '#ca8888ff','#2c7851ff', '#936c6cff', '#FFFFFF',],
            ],
        ];

        $cooltech = [
            [
                'nama' => 'Hijab',
                'image' => 'c01.png',
                'image_hover' => 'c01_hover.png',
                'warna' => ['#000000', '#FFFFFF', '#FF6C6C'],
            ],
            [
                'nama' => 'Hijab',
                'image' => 'c01.png',
                'image_hover' => 'c01_hover.png',
                'warna' => ['#000000', '#FFFFFF', '#FF6C6C'],
            ],
            [
                'nama' => 'Hijab',
                'image' => 'c01.png',
                'image_hover' => 'c01_hover.png',
                'warna' => ['#000000', '#FFFFFF', '#FF6C6C'],
            ],
            [
                'nama' => 'Hijab',
                'image' => 'c01.png',
                'image_hover' => 'c01_hover.png',
                'warna' => ['#000000', '#FFFFFF', '#FF6C6C'],
            ],
        ];

        return view('landingpage.index', [
            'bestproduk' => $bestproduk,
            'viscose' => $viscose,
            'cooltech' => $cooltech,
        ]);
    }

    public function collection(Request $request) {
        $filterKategori = $request->input('filter', []);
        if (!is_array($filterKategori)) {
            $filterKategori = [$filterKategori];
        }

        $produk = ProdukModel::with('kategori', 'bahan', 'fotoUtama', 'foto', 'warna', 'ukuran', 'toko');

        if (!empty($filterKategori)) {
            $produk->whereIn('kategori_id', $filterKategori);
        }

        $produk = $produk->get();
        $kategori = KategoriModel::all();

        return view('collection.index', [
            'produk' => $produk,
            'kategori' => $kategori,
            'filterkategori' => $filterKategori
        ]);
    }


    public function about() {
        $produk = ProdukModel::all();

        $rekomendasi = ProdukModel::with('kategori')
        ->get()
        ->unique(fn ($item) => $item->kategori_id)
        ->take(3); 

        return view('about.index', [
            'produk' => $produk,
            'rekomendasi' => $rekomendasi,
        ]);
    }
        
    public function show_produk($id)
    {
        // Memuat produk beserta relasi yang sesuai
        $produk = ProdukModel::with([
                'kategori',
                'bahan',
                'foto',
                'warna.warna', // sesuai nama relasi
                'ukuran.ukuran',
                'toko.toko'
            ])
            ->where('produk_id', $id)
            ->first();

        // Produk rekomendasi berdasarkan kategori yang berbeda
        $rekomendasi = ProdukModel::with('kategori')
            ->get()
            ->unique(fn ($item) => $item->kategori_id)
            ->take(3);

        return view('detail.index', [
            'produk' => $produk,
            'rekomendasi' => $rekomendasi,
        ]);
    }
}
