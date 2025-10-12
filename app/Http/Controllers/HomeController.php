<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmationMail;
use App\Models\BannerModel;
use App\Models\DetailTransaksiModel;
use App\Models\EkspedisiModel;
use App\Models\KategoriModel;
use Illuminate\Support\Facades\Http;
use App\Models\MetodePembayaranModel;
use App\Models\PembayaranModel;
use App\Models\FaqModel;
use Illuminate\Http\Request;
use App\Models\ProdukModel;
use App\Models\TransaksiModel;
use App\Models\VoucherModel;
use App\Rules\EmailActive;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    // CONTROLLER LANDING PAGE
    public function index()
    {
        $hero = Cache::remember('hero', 600, function () {
            return BannerModel::select('banner_id', 'nama_banner', 'foto_banner')
                ->get();
        });

        $newarrival = ProdukModel::select('produk_id', 'kategori_id', 'nama_produk', 'harga', 'diskon')
            ->with([
                'fotoUtama', 'hoverFoto'
            ])
            ->orderBy('produk_id', 'desc')
            ->take(8)
            ->get();

        $bestseller = Cache::remember('bestseller', 600, function () {
            return ProdukModel::select('produk_id', 'kategori_id','nama_produk', 'harga', 'diskon')
                ->with([
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
    // public function email()
    // {
    //     $order = [
    //         'kode_invoice' => 'ANS-12345678-123456789',
    //         'nama' => 'John Doe',
    //         'email' => 'coba@gmail.com',
    //         'alamat' => 'jl. berkah besar',
    //         'total' => 100000,
    //     ];
    //     $items = [
    //         [
    //             'nama' => 'Produk 1',
    //             'warna_nama' => 'Biru',
    //             'ukuran_nama' => 'L',
    //             'quantity' => 2,
    //             'harga' => 200000,
    //         ],
    //     ];
    //     return view('home.mail.invoice', compact('order', 'items'));
    // }

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

    public function homefaq()
    {
        $faqs = FaqModel::select('faq_id', 'pertanyaan', 'jawaban')->get();
        $rekomendasi = Cache::remember('rekomendasi', 600, function () {
            return ProdukModel::select('produk_id', 'nama_produk', 'kategori_id')
                ->with('kategori:kategori_id,nama_kategori')
                ->inRandomOrder()
                ->take(4)
                ->get();
        });

        return view('home.faq.index', compact( 'faqs', 'rekomendasi'));
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
        $hero = BannerModel::select('banner_id', 'nama_banner', 'foto_banner')
                ->where('banner_id', 19)
                ->first();

        $transaksi = TransaksiModel::with(['detail.produk', 'detail.ukuran', 'detail.warna', 'pembayaran'])
            ->where('kode_invoice', $kode_invoice)
            ->firstOrFail();

        $steps = config('transaksi.steps');

        $statusKeys = array_keys($steps);
        $stepIndex = array_search($transaksi->status_transaksi, $statusKeys);

        return view('home.checkout.transaksi', compact('transaksi', 'steps', 'stepIndex', 'hero'));
    }

    public function uploadBukti(Request $request, $pembayaran_id)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        $pembayaran = PembayaranModel::findOrFail($pembayaran_id);

        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->storeAs('bukti', $filename, 'public');

            $pembayaran->bukti_pembayaran = $filename;
            $pembayaran->save();

            return back()->with('success', 'Bukti pembayaran berhasil diunggah!');
        }

        return back()->with('error', 'Terjadi kesalahan saat mengunggah bukti pembayaran.');
    }

    // CONTROLLER CHECKOUT
    public function cart()
    {
        $cart = session()->get('cart', []);
        $total = collect($cart)->sum(fn($item) => $item['harga_diskon'] * $item['quantity']);

        $rekomendasi = Cache::remember('rekomendasi', 600, function () {
            return ProdukModel::select('produk_id', 'nama_produk', 'kategori_id')
                ->with('kategori:kategori_id,nama_kategori')
                ->inRandomOrder()
                ->take(4)
                ->get();
        });

        return view('home.cart.index', compact('cart', 'total', 'rekomendasi'));
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
            'harga' => $produk->harga,
            'diskon' => $produk->diskon,
            'harga_diskon' => $produk->diskon > 0 ? $produk->harga_diskon : $produk->harga,
            'warna_id' => $warnaData->warna_id,
            'warna_nama' => $warnaData->nama_warna,
            'ukuran_id' => $ukuranData->ukuran_id,
            'ukuran_nama' => $ukuranData->nama_ukuran,
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
        $total = collect($cart)->sum(fn($item) => $item['harga_diskon'] * $item['quantity']);
        $metode = MetodePembayaranModel::select('metode_pembayaran_id', 'metode_id', 'nama_pembayaran', 'kode_bayar', 'icon')
            ->with(['metode:metode_id,nama_metode'])
            ->get();

        $voucher = VoucherModel::where('status_voucher', true)
            ->where('min_transaksi', '<=', $total)
            ->whereDate('tanggal_mulai', '<=', now())
            ->whereDate('tanggal_berakhir', '>=', now())
            ->get();

        $ekspedisi = EkspedisiModel::select('ekspedisi_id', 'nama_ekspedisi', 'icon')
            ->get();

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong.');
        }

        return view('home.checkout.form', compact('cart', 'total', 'metode', 'voucher', 'ekspedisi'));
    }

    public function saveCheckout(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string',
            'telepon' => 'required|string',
            'email' => ['required', 'email', new EmailActive],
            'provinsi' => 'required|string',
            'kota' => 'required|string',
            'kecamatan' => 'required|string',
            'desa' => 'required|string',
            'alamat' => 'required|string',
            'metode_pembayaran_id' => 'required|exists:t_metode_pembayaran,metode_pembayaran_id',
            'ekspedisi_id' => 'required|exists:m_ekspedisi,ekspedisi_id',
            'voucher_id' => 'nullable|exists:m_voucher,voucher_id'
        ]);

        $fullAddress = "{$validated['alamat']}, {$validated['desa']}, {$validated['kecamatan']}, {$validated['kota']}, {$validated['provinsi']}";
        $telepon = $request->input('telepon');

        if (str_starts_with($telepon, '+62')) {
            $telepon = '0' . substr($telepon, 3);
        }

         $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Data tidak valid.');
        }

        $kode_invoice = null;
        
        DB::transaction(function () use ($cart, $telepon, $validated, $fullAddress, &$kode_invoice) {
            $total = collect($cart)->sum(fn($item) => $item['harga_diskon'] * $item['quantity']);
            $voucherId = $validated['voucher_id'] ?? null;
            $potongan = 0;

            if ($voucherId) {
                $voucher = VoucherModel::find($voucherId);
                if ($voucher && $voucher->isValid($total)) {
                    $potongan = $voucher->hitungPotongan($total);
                    $voucher->markAsUsed();
                } else {
                    $voucherId = null;
                }
            }

            $finalTotal = $total - $potongan;

            $pembayaran = PembayaranModel::create([
                'metode_pembayaran_id'  => $validated['metode_pembayaran_id'],
                'voucher_id'            => $voucherId,
                'jumlah_produk'         => count($cart),
                'total_harga'           => $finalTotal,
            ]);

            $transaksi = TransaksiModel::create([
                'pembayaran_id'     => $pembayaran->pembayaran_id,
                'ekspedisi_id'      => $validated['ekspedisi_id'],
                'nama_customer'     => $validated['nama'],
                'no_telp'           => $telepon,
                'email'             => $validated['email'],
                'alamat'            => $fullAddress
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

            $kode_invoice = $transaksi->kode_invoice;
        });

        Mail::to($validated['email'])->queue(
            new OrderConfirmationMail([
                'kode_invoice' => $kode_invoice,
                'nama'        => $validated['nama'],
                'email'       => $validated['email'],
                'alamat'      => $fullAddress,
                'total'       => $finalTotal ?? 0,
            ], $cart)
        );

        session()->forget(['cart', 'checkout_data']);

        return redirect()->route('transaksi.show', ['kode_invoice' => $kode_invoice]);
    }
}
