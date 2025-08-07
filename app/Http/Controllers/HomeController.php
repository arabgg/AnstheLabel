<?php

namespace App\Http\Controllers;

use App\Models\KategoriModel;
use Illuminate\Http\Request;
use App\Models\ProdukModel;

class HomeController extends Controller
{
    public function index() {
        $bestproduk = ProdukModel::with([
            'kategori',
            'foto' => fn($q) => $q->orderByDesc('status_foto'),
            'fotoUtama'
        ])
        ->latest('produk_id') // atau ->orderByDesc('id')
        ->take(4)
        ->get();

        $bestseller = ProdukModel::with([
            'kategori',
            'foto' => fn($q) => $q->orderByDesc('status_foto'),
            'fotoUtama'
        ])
        ->orderByDesc('harga') // urutkan berdasarkan harga tertinggi
        ->take(4) // ambil 4 produk teratas
        ->get();

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

        return view('landingpage.index', [
            'bestproduk' => $bestproduk,
            'bestseller' => $bestseller,
            'viscose' => $viscose,
        ]);
    }

    public function collection(Request $request) {
        $filterKategori = $request->input('filter', []);
        if (!is_array($filterKategori)) {
            $filterKategori = [$filterKategori];
        }

        $produk = ProdukModel::with(['kategori', 'bahan', 'fotoUtama', 'foto', 'warna.warna', 'ukuran', 'toko']);

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
        ->take(4); 

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
            ->take(4);

        return view('detail.index', [
            'produk' => $produk,
            'rekomendasi' => $rekomendasi,
        ]);
    }
}
