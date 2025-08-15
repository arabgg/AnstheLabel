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

        return view('home.landingpage.index', [
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

        return view('home.collection.index', [
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

        return view('home.about.index', [
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

        return view('home.detail.index', [
            'produk' => $produk,
            'rekomendasi' => $rekomendasi,
        ]);
    }

    public function cart()
    {
        $cart = session()->get('cart', []);
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['harga'] * $item['quantity'];
        }

        $grandTotal = $total;

        $rekomendasi = ProdukModel::with('kategori')
            ->get()
            ->unique(fn ($item) => $item->kategori_id)
            ->take(4);

        return view('home.cart.index', compact('cart', 'total', 'grandTotal', 'rekomendasi'));
    }

    public function add_cart(Request $request)
    {
        $produk = ProdukModel::findOrFail($request->produk_id);

        $warnaData = \App\Models\WarnaModel::find($request->warna);
        $ukuranData = \App\Models\UkuranModel::find($request->ukuran);

        $cart = session()->get('cart', []);

        $cart[] = [
            'id' => $produk->produk_id,
            'nama' => $produk->nama_produk,
            'harga' => $produk->diskon > 0 ? $produk->harga_diskon : $produk->harga,
            'warna_id' => $warnaData?->warna_id,
            'warna_nama' => $warnaData?->nama_warna ?? '-', 
            'ukuran_id' => $ukuranData?->ukuran_id,
            'ukuran_nama' => $ukuranData?->nama_ukuran ?? '-',
            'quantity' => (int) $request->quantity,
            'foto' => $produk->fotoUtama ? $produk->fotoUtama->foto_produk : null
        ];

        session()->put('cart', $cart);

        if ($request->action === 'buy_now') {
            return redirect()->route('cart.index');
        } else {
            return response()->json(['success' => true]);
        }
    }


    public function update_cart(Request $request)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$request->index])) {
            $cart[$request->index]['quantity'] = (int) $request->quantity;
            session()->put('cart', $cart);
        }
        return redirect()->route('cart.index');
    }

    public function remove_cart(Request $request)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$request->index])) {
            unset($cart[$request->index]);
            session()->put('cart', array_values($cart));
        }
        return redirect()->route('cart.index');
    }

    public function checkout()
    {
        return view('home.checkout.index');
    }

    public function checkoutpp()
    {
        session()->forget('cart'); // Kosongkan keranjang
        return redirect()->route('cart.success');
    }

    public function success()
    {
        return view('cart.success');
    }
}
