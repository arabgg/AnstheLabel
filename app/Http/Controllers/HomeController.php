<?php

namespace App\Http\Controllers;

use App\Models\BahanModel;
use App\Models\BannerModel;
use App\Models\KategoriModel;
use App\Models\FaqModel;
use App\Models\MetodePembayaranModel;
use Illuminate\Http\Request;
use App\Models\ProdukModel;
use App\Models\TransaksiModel;
use App\Models\UkuranModel;
use App\Models\WarnaModel;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    // CONTROLLER LANDING PAGE
    public function index()
    {
        $hero = BannerModel::select('banner_id', 'nama_banner', 'foto_banner')
            ->get();

        $desc = BannerModel::select('banner_id', 'deskripsi')
            ->where('banner_id', 19)
            ->first();

        $newarrival = ProdukModel::select('produk_id', 'kategori_id', 'nama_produk', 'harga', 'diskon')
            ->with([
                'fotoUtama', 'hoverFoto'
            ])
            ->orderBy('produk_id', 'desc')
            ->take(8)
            ->get();

        $bestseller = ProdukModel::select('produk_id', 'kategori_id','nama_produk', 'harga', 'diskon')
            ->with([
                'fotoUtama', 'hoverFoto'
            ])
            ->orderByDesc('harga')
            ->take(4)
            ->get();

        $bestproduk = ProdukModel::select('produk_id', 'nama_produk', 'is_best')
            ->with(['fotoUtama', 'hoverFoto'])
            ->where('is_best', 1)
            ->take(5)
            ->get();

        $edition = ProdukModel::select('produk_id', 'nama_produk', 'kategori_id')
            ->with(['kategori:kategori_id,nama_kategori', 'fotoUtama', 'hoverFoto'])
            ->take(8)
            ->get();

        return view('home.landingpage.index', compact('newarrival', 'bestseller', 'bestproduk', 'edition', 'hero', 'desc'));
    }
    
    public function collection(Request $request)
    {
        $hero = BannerModel::select('banner_id', 'nama_banner', 'foto_banner')
            ->get();

        $desc = BannerModel::select('banner_id', 'deskripsi')
            ->where('banner_id', 19)
            ->first();

        $filterKategori = (array) $request->input('filter', []);
        $filterBahan    = (array) $request->input('bahan', []);
        $filterWarna    = (array) $request->input('warna', []);
        $filterUkuran   = (array) $request->input('ukuran', []);

        $searchQuery = $request->input('search', '');
        $sort = $request->input('sort', '');
        
        $produk = ProdukModel::select('produk_id', 'nama_produk', 'harga', 'diskon', 'kategori_id')
            ->with(['kategori:kategori_id,nama_kategori', 'fotoUtama'])
            ->when(!empty($filterKategori), fn($q) => $q->whereIn('kategori_id', $filterKategori))
            ->when(!empty($filterBahan), fn($q)=>$q->whereIn('bahan_id',$filterBahan))

        ->when(!empty($filterWarna), function($q) use($filterWarna){
            $q->whereHas('warna', fn($q2)=>$q2->whereIn('warna_id',$filterWarna));
        })

        ->when(!empty($filterUkuran), function($q) use($filterUkuran){
            $q->whereHas('ukuran', fn($q2)=>$q2->whereIn('ukuran_id',$filterUkuran));
        })

            ->when(!empty($searchQuery), function ($q) use ($searchQuery) {
                $keywords = explode(' ', $searchQuery);
                $q->where(function($q2) use ($keywords) {
                    foreach ($keywords as $word) {
                        $q2->orWhere('nama_produk', 'like', "%{$word}%");
                    }
                });
            })
            ->when($sort === 'terbaru', fn($q) => $q->orderBy('produk_id', 'DESC'))
            ->when($sort === 'termahal', fn($q) => $q->orderBy('harga', 'DESC'))
            ->paginate(100);

        $kategori = KategoriModel::select('kategori_id', 'nama_kategori')->get();
         $bahan    = BahanModel::select('bahan_id','nama_bahan')->get();
    $warna    = WarnaModel::select('warna_id','nama_warna')->get();
    $ukuran   = UkuranModel::select('ukuran_id','nama_ukuran')->get();

        return view('home.collection.index', compact('produk','kategori','bahan','warna','ukuran',
        'filterKategori','filterBahan','filterWarna','filterUkuran',
        'searchQuery','sort','hero','desc'));
    }

    public function about()
    {
        $desc = BannerModel::select('banner_id', 'deskripsi')
            ->where('banner_id', 19)
            ->first();

        $rekomendasi = ProdukModel::select('produk_id', 'nama_produk', 'kategori_id')
            ->with('kategori:kategori_id,nama_kategori')
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('home.about.index', compact('rekomendasi', 'desc'));
    }

    public function homefaq()
    {
        $desc = BannerModel::select('banner_id', 'deskripsi')
            ->where('banner_id', 19)
            ->first();

        $faqs = FaqModel::select('faq_id', 'pertanyaan', 'jawaban')->get();

        $rekomendasi = ProdukModel::select('produk_id', 'nama_produk', 'kategori_id')
            ->with('kategori:kategori_id,nama_kategori')
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('home.faq.index', compact( 'faqs', 'rekomendasi', 'desc'));
    }

    public function show_produk($id)
    {
        $desc = BannerModel::select('banner_id', 'deskripsi')
            ->where('banner_id', 19)
            ->first();

        $produk = ProdukModel::with([
                'kategori:kategori_id,nama_kategori',
                'bahan:bahan_id,nama_bahan,deskripsi',
                'foto:foto_produk_id,produk_id,foto_produk,status_foto',
                'fotoUtama',
                'warna.warna:warna_id,kode_hex',
                'ukuran.ukuran:ukuran_id,nama_ukuran,deskripsi',
            ])
            ->select('produk_id', 'nama_produk', 'harga', 'diskon', 'deskripsi', 'kategori_id', 'bahan_id')
            ->findOrFail($id);

        $rekomendasi = ProdukModel::select('produk_id', 'nama_produk', 'kategori_id')
                ->with('kategori:kategori_id,nama_kategori')
                ->inRandomOrder()
                ->take(4)
                ->get();

        return view('home.detail.index', compact('produk', 'rekomendasi'));
    }

    public function invoice()
    {
        $bannerHeader = BannerModel::select('banner_id', 'nama_banner', 'deskripsi')
            ->where('nama_banner', 'Banner Header')
            ->first();

        return view('home.checkout.invoice', compact('bannerHeader'));
    }

    public function cekInvoice(Request $request)
    {
        $request->validate([
            'kode_invoice' => 'required|string'
        ]);

        $transaksi = TransaksiModel::select('transaksi_id', 'kode_invoice')
            ->where('kode_invoice', $request->kode_invoice)
            ->first();

        if ($transaksi) {
            return redirect()->route('transaksi.show', $transaksi->kode_invoice);
        }

        return back()->with('error', 'Kode Invoice tidak ditemukan');
    }

    public function transaksi($kode_invoice)
    {
        $bannerHeader = BannerModel::select('banner_id', 'nama_banner', 'deskripsi')
            ->where('nama_banner', 'Banner Header')
            ->first();

        $hero = BannerModel::select('banner_id', 'nama_banner', 'foto_banner')
            ->where('banner_id', 20)
            ->first();

        $transaksi = TransaksiModel::with(['detail.produk', 'detail.ukuran', 'detail.warna', 'pembayaran'])
            ->where('kode_invoice', $kode_invoice)
            ->firstOrFail();

        $steps = config('transaksi.steps');

        $statusKeys = array_keys($steps);
        $stepIndex = array_search($transaksi->status_transaksi, $statusKeys);

        return view('home.checkout.transaksi', compact('transaksi', 'steps', 'stepIndex', 'hero', 'bannerHeader'));
    }


    // CONTROLLER CHECKOUT
    public function cart()
    {
        $cart = session()->get('cart', []);
        $total = collect($cart)->sum(fn($item) => $item['harga'] * $item['quantity']);

        $rekomendasi = ProdukModel::select('produk_id', 'nama_produk', 'kategori_id')
                ->with('kategori:kategori_id,nama_kategori')
                ->inRandomOrder()
                ->take(4)
                ->get();

        return view('home.cart.index', compact('cart', 'total', 'rekomendasi'))
            ->with('grandTotal', $total);
    }

    public function add_cart(Request $request)
    {
        $produk = ProdukModel::select('produk_id', 'nama_produk', 'harga', 'diskon')
            ->with('fotoUtama')
            ->findOrFail($request->produk_id);

        $warnaData = \App\Models\WarnaModel::select('warna_id', 'nama_warna')->find($request->warna);
        $ukuranData = \App\Models\UkuranModel::select('ukuran_id', 'nama_ukuran')->find($request->ukuran);

        $cart = session()->get('cart', []);

        $cart[] = [
            'produk_id' => $produk->produk_id,
            'nama' => $produk->nama_produk,
            'harga' => $produk->diskon > 0 ? $produk->harga_diskon : $produk->harga,
            'warna_id' => $warnaData?->warna_id,
            'warna_nama' => $warnaData?->nama_warna,
            'ukuran_id' => $ukuranData?->ukuran_id,
            'ukuran_nama' => $ukuranData?->nama_ukuran,
            'quantity' => (int) $request->quantity,
            'foto' => $produk->fotoUtama->foto_produk ?? null,
        ];

        session()->put('cart', $cart);

        return $request->action === 'buy_now'
            ? redirect()->route('cart.index')
            : response()->json(['success' => true]);
    }

    public function update_cart(Request $request)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$request->index])) {
            $quantity = max(0, (int) $request->quantity);

            if ($quantity === 0) {
                unset($cart[$request->index]);
            } else {
                $cart[$request->index]['quantity'] = $quantity;
            }

            session()->put('cart', array_values($cart));
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

    public function provinsi()
    {
        $response = Http::withoutVerifying()
            ->get("https://emsifa.github.io/api-wilayah-indonesia/api/provinces.json");

        return response()->json($response->json());
    }

    public function kota($provinsi_id)
    {
        $response = Http::withoutVerifying()
            ->get("https://emsifa.github.io/api-wilayah-indonesia/api/regencies/{$provinsi_id}.json");
        return response()->json($response->json(), $response->status());
    }

    public function kecamatan($kota_id)
    {
        $response = Http::withoutVerifying()
            ->get("https://emsifa.github.io/api-wilayah-indonesia/api/districts/{$kota_id}.json");
        return response()->json($response->json(), $response->status());
    }

    public function desa($kecamatan_id)
    {
        $response = Http::withoutVerifying()
            ->get("https://emsifa.github.io/api-wilayah-indonesia/api/villages/{$kecamatan_id}.json");
        return response()->json($response->json(), $response->status());
    }

    public function checkoutForm()
    {
        $cart = session()->get('cart', []);
        $total = collect($cart)->sum(fn($item) => $item['harga'] * $item['quantity']);
        $metode = MetodePembayaranModel::select('metode_pembayaran_id', 'metode_id', 'nama_pembayaran', 'kode_bayar', 'icon')
            ->with(['metode:metode_id,nama_metode'])
            ->get();

        return view('home.detail.index', compact('produk', 'rekomendasi', 'desc'));
    }
}
