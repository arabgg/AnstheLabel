<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmationMail;
use App\Models\BannerModel;
use App\Models\DetailTransaksiModel;
use App\Models\EkspedisiModel;
use App\Models\MetodePembayaranModel;
use App\Models\PembayaranModel;
use App\Models\ProdukModel;
use App\Models\TransaksiModel;
use App\Models\VoucherModel;
use App\Rules\EmailActive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    public function cart()
    {
        $desc = BannerModel::select('banner_id', 'deskripsi')
            ->where('banner_id', 19)
            ->first();

        $cart = session()->get('cart', []);
        $total = collect($cart)->sum(fn($item) => $item['harga_diskon'] * $item['quantity']);

        $rekomendasi = ProdukModel::select('produk_id', 'nama_produk', 'kategori_id')
            ->with('kategori:kategori_id,nama_kategori')
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('home.cart.index', compact('cart', 'total', 'rekomendasi', 'desc'));
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

    public function checkoutForm()
    {
        $desc = BannerModel::select('banner_id', 'deskripsi')
            ->where('banner_id', 19)
            ->first();

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

        return view('home.checkout.form', compact('cart', 'total', 'metode', 'voucher', 'ekspedisi', 'desc'));
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
        
        DB::transaction(function () use ($cart, $telepon, $validated, $fullAddress, &$kode_invoice, &$finalTotal) {
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

    public function transaksi($kode_invoice)
    {
        $hero = BannerModel::select('banner_id', 'nama_banner', 'foto_banner')
                ->where('banner_id', 20)
                ->first();

        $desc = BannerModel::select('banner_id', 'deskripsi')
            ->where('banner_id', 19)
            ->first();

        $transaksi = TransaksiModel::with(['detail.produk', 'detail.ukuran', 'detail.warna', 'pembayaran'])
            ->where('kode_invoice', $kode_invoice)
            ->firstOrFail();

        $steps = config('transaksi.steps');

        $statusKeys = array_keys($steps);
        $stepIndex = array_search($transaksi->status_transaksi, $statusKeys);

        return view('home.checkout.transaksi', compact('transaksi', 'steps', 'stepIndex', 'hero', 'desc'));
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
}
