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
                'nama' => 'Sleveless Round Neck',
                'kategori' => 'Dress',
                'image' => 'vs01.png',
                'image_hover' => 'vs01_hover.png',
                'warna' => ['#000000', '#FF6C6C', '#2c7851ff', '#8d5858ff', '#ca8888ff', '#936c6cff', '#FFFFFF',],
            ],
            [
                'nama' => 'Sleveless Turtle Neck',
                'kategori' => 'Dress',
                'image' => 'vs02.png',
                'image_hover' => 'vs02_hover.png',
                'warna' => ['#000000', '#8d5858ff', '#ca8888ff', '#FF6C6C', '#2c7851ff', '#936c6cff', '#FFFFFF',],
            ],
            [
                'nama' => 'Kutton Strip Knitwear',
                'kategori' => 'Outer',
                'image' => 'vs03.png',
                'image_hover' => 'vs03_hover.png',
                'warna' => ['#FF6C6C', '#2c7851ff', '#8d5858ff', '#ca8888ff', '#936c6cff', '#000000', '#FFFFFF'],
            ],
            [
                'nama' => 'Savana Cardigan',
                'kategori' => 'Outer',
                'image' => 'vs04.png',
                'image_hover' => 'vs04_hover.png',
                'warna' => ['#000000', '#FF6C6C', '#8d5858ff', '#ca8888ff','#2c7851ff', '#936c6cff', '#FFFFFF',],
            ],
        ];

        return view('landingpage.index', [
            'bestproduk' => $bestproduk,
            'bestseller' => $bestseller,
            'viscose' => $viscose,
        ]);
    }

    public function collection(Request $request)
    {
        // Ambil filter kategori
        $filterKategori = $request->input('filter', []);
        if (!is_array($filterKategori)) {
            $filterKategori = [$filterKategori];
        }

        // Ambil keyword pencarian
        $searchQuery = $request->input('search', '');

        // Query produk dengan relasi
        $produk = ProdukModel::with(['kategori', 'bahan', 'fotoUtama', 'foto', 'warna.warna', 'ukuran']);

        // Filter kategori
        if (!empty($filterKategori)) {
            $produk->whereIn('kategori_id', $filterKategori);
        }

        // Filter search (pencarian nama produk)
        if (!empty($searchQuery)) {
            $produk->where('nama_produk', 'like', '%' . $searchQuery . '%');
        }

        // Ambil data produk
        $produk = $produk->get();

        // Ambil semua kategori
        $kategori = KategoriModel::all();

        return view('collection.index', [
            'produk' => $produk,
            'kategori' => $kategori,
            'filterkategori' => $filterKategori,
            'searchQuery' => $searchQuery
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

    public function addToCart(Request $request)
    {
        $action = $request->input('action');

        $request->validate([
            'produk_id' => 'required|exists:t_produk,produk_id',
            'warna' => 'required|string',
            'ukuran' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        $produk = ProdukModel::findOrFail($request->produk_id);

        // Simulasi menyimpan cart di session (bisa modifikasi sesuai kebutuhan)
        $cart = session()->get('cart', []);

        $cartKey = $request->produk_id.'-'.$request->warna.'-'.$request->ukuran;

        if(isset($cart[$cartKey])){
            $cart[$cartKey]['quantity'] += $request->quantity;
        } else {
            $cart[$cartKey] = [
                'produk_id' => $produk->produk_id,
                'nama_produk' => $produk->nama_produk,
                'warna' => $request->warna,
                'ukuran' => $request->ukuran,
                'quantity' => $request->quantity,
                'harga' => $produk->harga_diskon ?? $produk->harga,
                'foto' => $produk->fotoUtama ? asset('storage/foto_produk/' . $produk->fotoUtama->foto_produk) : '',
            ];
        }

        session()->put('cart', $cart);

        if ($action === 'buy_now') {
            return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan dan siap dibeli.');
        } else {
            return response()->json(['success' => true]);
        }
    }

    public function cart()
    {
        $cart = session()->get('cart', []);
        return view('cart.index', compact('cart'));
    }
}
