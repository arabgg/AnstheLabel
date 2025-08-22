<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaksiModel;
use App\Models\KategoriModel;
use App\Models\MetodeModel;
use App\Models\PembayaranModel;
use Illuminate\Http\Request;
use App\Models\ProdukModel;
use App\Models\TransaksiModel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $newarrival = Cache::remember('newarrival', 600, function () {
            return ProdukModel::select('produk_id', 'nama_produk', 'harga', 'diskon')
                ->with([
                    'kategori:kategori_id,nama_kategori',
                    'fotoUtama'
                ])
                ->latest('produk_id')
                ->take(4)
                ->get();
        });

        $bestseller = Cache::remember('bestseller', 600, function () {
            return ProdukModel::select('produk_id', 'nama_produk', 'harga', 'diskon')
                ->with([
                    'kategori:kategori_id,nama_kategori',
                    'fotoUtama'
                ])
                ->orderByDesc('harga')
                ->take(4)
                ->get();
        });

        $viscose = config('viscose');

        return view('home.landingpage.index', compact('newarrival', 'bestseller', 'viscose'));
    }

    public function collection(Request $request)
    {
        $filterKategori = (array) $request->input('filter', []);
        $searchQuery = $request->input('search', '');
        $produk = ProdukModel::with('kategori', 'bahan', 'fotoUtama', 'foto', 'warna', 'ukuran');

        $cacheKey = 'produk_' . md5(json_encode($filterKategori) . '_' . $searchQuery);
        
        $produk = Cache::remember($cacheKey, 600, function() use ($filterKategori, $searchQuery) {
            return ProdukModel::select('produk_id', 'nama_produk', 'harga', 'diskon', 'kategori_id')
                ->with(['kategori:kategori_id,nama_kategori', 'fotoUtama'])
                ->when(!empty($filterKategori), fn($q) => $q->whereIn('kategori_id', $filterKategori))
                ->when(!empty($searchQuery), fn($q) => $q->where('nama_produk', 'like', "%{$searchQuery}%"))
                ->paginate(100);
        });

        $kategori = Cache::remember('kategori', 600, fn() => KategoriModel::select('kategori_id', 'nama_kategori')->get());

        return view('home.collection.index', compact('produk', 'kategori', 'filterKategori', 'searchQuery'));
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
        $produk = Cache::remember('produk_{$id}', 600, function () use ($id) {
            return ProdukModel::with([
                    'kategori:kategori_id,nama_kategori',
                    'bahan:bahan_id,nama_bahan,deskripsi',
                    'fotoUtama',
                    'warna.warna:warna_id,kode_hex',
                    'ukuran.ukuran:ukuran_id,nama_ukuran,deskripsi',
                ])
                ->select('produk_id', 'nama_produk', 'harga', 'diskon', 'deskripsi', 'kategori_id', 'bahan_id')
                ->findOrFail($id);
        });

        $rekomendasi = Cache::remember('rekomendasi', 600, function () {
            return ProdukModel::select('produk_id', 'nama_produk', 'kategori_id')
                ->with('kategori:kategori_id,nama_kategori')
                ->inRandomOrder()
                ->take(4)
                ->get();
        });

        return view('home.detail.index', compact('produk', 'rekomendasi'));
    }

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

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong.');
        }

        return view('home.checkout.form', compact('cart', 'total'));
    }

    public function saveCheckout(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'telepon' => 'required|string',
            'email' => 'required|email',
            'alamat' => 'required|string',
            'kota' => 'required|string',
            'kecamatan' => 'required|string',
        ]);

        $fullAddress = $request->alamat . ', ' . $request->kota . ', ' . $request->kecamatan;

        session()->put('checkout_data', [
            'nama'    => $request->nama,
            'telepon' => $request->telepon,
            'email'   => $request->email,
            'alamat'  => $fullAddress,
        ]);

        return redirect()->route('checkout.payment');
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

        DB::transaction(function () use ($cart, $checkoutData, $request) {
            // 1️⃣ Simpan transaksi
            $transaksi = TransaksiModel::create([
                'nama_customer'     => $checkoutData['nama'],
                'no_telp'           => $checkoutData['telepon'],
                'email'             => $checkoutData['email'],
                'alamat'            => $checkoutData['alamat'],
            ]);

            // 2️⃣ Simpan pembayaran
            $total = collect($cart)->sum(fn($item) => $item['harga'] * $item['quantity']);
            $pembayaran = PembayaranModel::create([
                'metode_id'         => $request->metode_id,
                'jumlah_produk'     => count($cart),
                'total_harga'       => $total,
            ]);

            // 3️⃣ Simpan detail transaksi
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
        });

        session()->forget(['cart', 'checkout_data']);

        return redirect()->route('cart.index')->with('success', 'Transaksi berhasil dibuat!');
    }
}
