<?php

namespace App\Http\Controllers;

use App\Models\BannerModel;
use App\Models\DetailTransaksiModel;
use App\Models\KategoriModel;
use App\Models\MetodeModel;
use App\Models\MetodePembayaranModel;
use App\Models\PembayaranModel;
use Illuminate\Http\Request;
use App\Models\ProdukModel;
use App\Models\TransaksiModel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    // CONTROLLER LANDING PAGE
    public function index()
    {
        $hero = Cache::remember('hero', 600, function () {
            return BannerModel::select('banner_id', 'nama_banner', 'foto_banner')
                ->get();
        });

        $newarrival = Cache::remember('newarrival', 600, function () {
            return ProdukModel::select('produk_id', 'nama_produk', 'harga', 'diskon')
                ->with([
                    'kategori:kategori_id,nama_kategori',
                    'fotoUtama', 'hoverFoto'
                ])
                ->latest('produk_id')
                ->take(4)
                ->get();
        });

        $bestseller = Cache::remember('bestseller', 600, function () {
            return ProdukModel::select('produk_id', 'nama_produk', 'harga', 'diskon')
                ->with([
                    'kategori:kategori_id,nama_kategori',
                    'fotoUtama', 'hoverFoto'
                ])
                ->orderByDesc('harga')
                ->take(4)
                ->get();
        });

        $bestproduk = Cache::remember('bestproduk', 600, function () {
            return ProdukModel::select('produk_id', 'nama_produk', 'is_best')
                ->with(['fotoUtama', 'hoverFoto'])
                ->where('is_best', 1)
                ->take(5)
                ->get();
        });

        $edition = Cache::remember('edition', 600, function () {
            return ProdukModel::select('produk_id', 'nama_produk', 'kategori_id')
                ->with(['kategori:kategori_id,nama_kategori', 'fotoUtama', 'hoverFoto'])
                ->take(8)
                ->get();
        });

        return view('home.landingpage.index', compact('newarrival', 'bestseller', 'bestproduk', 'edition', 'hero'));
    }

    public function collection(Request $request)
    {
        $hero = Cache::remember('hero', 600, function () {
            return BannerModel::select('banner_id', 'nama_banner', 'foto_banner')
                ->get();
        });

        $filterKategori = (array) $request->input('filter', []);
        $searchQuery = $request->input('search', '');
        
        $produk = ProdukModel::select('produk_id', 'nama_produk', 'harga', 'diskon', 'kategori_id')
            ->with(['kategori:kategori_id,nama_kategori', 'fotoUtama'])
            ->when(!empty($filterKategori), fn($q) => $q->whereIn('kategori_id', $filterKategori))
            ->when(!empty($searchQuery), fn($q) => $q->where('nama_produk', 'like', "%{$searchQuery}%"))
            ->paginate(100);

        $kategori = KategoriModel::select('kategori_id', 'nama_kategori')->get();

        return view('home.collection.index', compact('produk', 'kategori', 'filterKategori', 'searchQuery', 'hero'));
    }

    public function about()
    {
        $rekomendasi = Cache::remember('rekomendasi', 600, function () {
            return ProdukModel::select('produk_id', 'nama_produk', 'kategori_id')
                ->with('kategori:kategori_id,nama_kategori')
                ->inRandomOrder()
                ->take(4)
                ->get();
        });

        return view('home.about.index', compact('rekomendasi'));
    }

    public function show_produk($id)
    {
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

        $rekomendasi = Cache::remember('rekomendasi', 600, function () {
            return ProdukModel::select('produk_id', 'nama_produk', 'kategori_id')
                ->with('kategori:kategori_id,nama_kategori')
                ->inRandomOrder()
                ->take(4)
                ->get();
        });

        return view('home.detail.index', compact('produk', 'rekomendasi'));
    }

    public function invoice()
    {
        return view('home.checkout.invoice');
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
        $transaksi = TransaksiModel::with(['detail.produk', 'detail.ukuran', 'detail.warna', 'detail.pembayaran'])
            ->where('kode_invoice', $kode_invoice)
            ->firstOrFail();

        // Ambil step dari config
        $steps = config('transaksi.steps');

        // Cari index step berdasarkan status sekarang
        $statusKeys = array_keys($steps);
        $stepIndex = array_search($transaksi->status_transaksi, $statusKeys);

        return view('home.checkout.transaksi', compact('transaksi', 'steps', 'stepIndex'));
    }


    // CONTROLLER CHECKOUT
    public function cart()
    {
        $cart = session()->get('cart', []);
        $total = collect($cart)->sum(fn($item) => $item['harga'] * $item['quantity']);

        $rekomendasi = Cache::remember('rekomendasi', 600, function () {
            return ProdukModel::select('produk_id', 'nama_produk', 'kategori_id')
                ->with('kategori:kategori_id,nama_kategori')
                ->inRandomOrder()
                ->take(4)
                ->get();
        });

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

    public function checkoutForm()
    {
        $cart = session()->get('cart', []);
        $total = collect($cart)->sum(fn($item) => $item['harga'] * $item['quantity']);
        $metode = MetodeModel::select('metode_id', 'nama_metode')
            ->with(['mPembayaran:metode_pembayaran_id,nama_pembayaran,kode_bayar,icon'])
            ->get();

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong.');
        }

        return view('home.checkout.form', compact('cart', 'total', 'metode'));
    }

    public function saveCheckout(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string',
            'telepon' => 'required|string',
            'email' => 'required|email',
            'alamat' => 'required|string',
            'kota' => 'required|string',
            'kecamatan' => 'required|string',
            'metode_pembayaran_id' => 'required|exists:t_metode_pembayaran,metode_pembayaran_id'
        ]);

        $fullAddress = "{$validated['alamat']}, {$validated['kota']}, {$validated['kecamatan']}";

        $cart = session()->get('cart', []);
        $checkoutData = session()->get('checkout_data');

        if (!$checkoutData || empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Data tidak valid.');
        }

        $transaksiId = null;
        
        DB::transaction(function () use ($cart, $validated, $fullAddress, &$transaksiId) {
            $transaksi = TransaksiModel::create([
                'nama_customer'     => $validated['nama'],
                'no_telp'           => $validated['telepon'],
                'email'             => $validated['email'],
                'alamat'            => $fullAddress
            ]);

            $total = collect($cart)->sum(fn($item) => $item['harga'] * $item['quantity']);

            $pembayaran = PembayaranModel::create([
                'metode_pembayaran_id'  => $validated['metode_pembayaran_id'],
                'jumlah_produk'         => count($cart),
                'total_harga'           => $total,
            ]);

            foreach ($cart as $item) {
                DetailTransaksiModel::create([
                    'transaksi_id'   => $transaksi->transaksi_id,
                    'pembayaran_id'  => $pembayaran->pembayaran_id,
                    'produk_id'      => $item['produk_id'],
                    'ukuran_id'      => $item['ukuran_id'],
                    'warna_id'       => $item['warna_id'],
                    'jumlah'         => $item['quantity'],
                ]);
            }
            $transaksiId = $transaksi->transaksi_id; 
        });

        session()->forget(['cart', 'checkout_data']);

        return redirect()->route('checkout.success', ['transaksi_id' => $transaksiId]);
    }

    public function paymentForm()
    {
        $cart = session()->get('cart', []);
        $checkoutData = session()->get('checkout_data');
        $total = collect($cart)->sum(fn($item) => $item['harga'] * $item['quantity']);

        if (!$checkoutData || empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Data checkout tidak valid.');
        }

        $paymentMethods = MetodeModel::select('metode_id', 'nama_metode')->get();

        return view('home.checkout.payment', compact('cart', 'checkoutData', 'paymentMethods', 'total'));
    }

    public function processPayment(Request $request)
    {
        $request->validate([
            'metode_id' => 'required|exists:m_metode_pembayaran,metode_id'
        ]);

        $cart = session()->get('cart', []);
        $checkoutData = session()->get('checkout_data');

        if (!$checkoutData || empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Data tidak valid.');
        }

        $detailId = null;

        DB::transaction(function () use ($cart, $checkoutData, $request, &$detailId) {
            $transaksi = TransaksiModel::create([
                'nama_customer'     => $checkoutData['nama'],
                'no_telp'           => $checkoutData['telepon'],
                'email'             => $checkoutData['email'],
                'alamat'            => $checkoutData['alamat'],
            ]);

            $total = collect($cart)->sum(fn($item) => $item['harga'] * $item['quantity']);

            $pembayaran = PembayaranModel::create([
                'metode_id'         => $request->metode_id,
                'jumlah_produk'     => count($cart),
                'total_harga'       => $total,
            ]);

            foreach ($cart as $item) {
                $detail = DetailTransaksiModel::create([
                    'transaksi_id'   => $transaksi->transaksi_id,
                    'pembayaran_id'  => $pembayaran->pembayaran_id,
                    'produk_id'      => $item['produk_id'],
                    'ukuran_id'      => $item['ukuran_id'],
                    'warna_id'       => $item['warna_id'],
                    'jumlah'         => $item['quantity'],
                ]);

                $detailId = $detail->detail_transaksi_id;
            }
        });

        session()->forget(['cart', 'checkout_data']);

        return redirect()->route('checkout.success', ['detail_id' => $detailId]);
    }


    public function paymentSuccess($detail_id)
    {
        $detail = DetailTransaksiModel::select('detail_transaksi_id', 'transaksi_id', 'pembayaran_id')
            ->with([
                'transaksi:transaksi_id,kode_invoice',
                'pembayaran.metode:metode_id,nama_metode,kode_bayar'
            ])
            ->findOrFail($detail_id);

        return view('home.checkout.success', compact('detail'));
    }
}
