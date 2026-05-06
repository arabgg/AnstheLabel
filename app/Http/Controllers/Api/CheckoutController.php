<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MetodePembayaranModel;
use App\Models\MetodeModel;
use App\Models\VoucherModel;
use App\Models\EkspedisiModel;
use App\Models\ProdukModel;
use App\Models\UkuranModel;
use App\Models\WarnaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    /**
     * Get dependencies for checkout (payment methods, ekspedisi, etc)
     * GET /api/v1/checkout-dependencies
     */
    public function dependencies()
    {
        try {
            $metode = Cache::remember('api_metode_pembayaran', 3600, function () {
                return MetodeModel::with(['metodepembayaran' => function ($query) {
                    $query->where('status_pembayaran', 1);
                }])->get();
            });

            $formattedMetode = $metode->map(function ($m) {
                return [
                    'metode_id' => $m->metode_id,
                    'nama_metode' => $m->nama_metode,
                    'pembayaran' => $m->metodepembayaran->map(function ($p) {
                        return [
                            'metode_pembayaran_id' => $p->metode_pembayaran_id,
                            'nama_pembayaran' => $p->nama_pembayaran,
                            'kode_bayar' => $p->kode_bayar,
                            'atas_nama' => $p->atas_nama,
                            'tipe_kode_bayar' => $p->kode_bayar_type, // 'text', 'image', 'empty'
                            'icon' => $p->icon ? url('storage/icon_pembayaran/' . $p->icon) : null,
                        ];
                    }),
                ];
            })->filter(function ($m) {
                return $m['pembayaran']->count() > 0;
            })->values();

            $ekspedisi = Cache::remember('api_ekspedisi', 3600, function () {
                return EkspedisiModel::where('status_ekspedisi', 1)->get();
            });

            $formattedEkspedisi = $ekspedisi->map(function ($e) {
                return [
                    'ekspedisi_id' => $e->ekspedisi_id,
                    'nama_ekspedisi' => $e->nama_ekspedisi,
                    'icon' => $e->icon ? url('storage/icon_ekspedisi/' . $e->icon) : null,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Checkout dependencies berhasil diambil',
                'data' => [
                    'metode_pembayaran' => $formattedMetode,
                    'ekspedisi' => $formattedEkspedisi,
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil checkout dependencies',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Validate voucher code
     * POST /api/v1/checkout/validate-voucher
     */
    public function validateVoucher(Request $request)
    {
        try {
            $request->validate([
                'kode_voucher' => 'required|string',
                'total_belanja' => 'required|numeric|min:0',
            ]);

            $voucher = VoucherModel::where('kode_voucher', $request->kode_voucher)->first();

            if (!$voucher) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode voucher tidak ditemukan',
                ], 404);
            }

            $totalBelanja = (float) $request->total_belanja;
            $isValid = $voucher->isValid($totalBelanja);
            $potongan = $voucher->hitungPotongan($totalBelanja);

            return response()->json([
                'success' => true,
                'message' => $isValid ? 'Voucher valid' : 'Voucher tidak berlaku',
                'data' => [
                    'kode_voucher' => $voucher->kode_voucher,
                    'deskripsi' => $voucher->deskripsi,
                    'tipe_diskon' => $voucher->tipe_diskon,
                    'nilai_diskon' => (float) $voucher->nilai_diskon,
                    'potongan' => $potongan,
                    'is_valid' => $isValid,
                    'sisa_penggunaan' => $voucher->usage_limit !== null
                        ? $voucher->usage_limit - $voucher->used
                        : null,
                    'tanggal_berakhir' => $voucher->tanggal_berakhir?->toIso8601String(),
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal validasi voucher',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Calculate cart summary
     * POST /api/v1/checkout/calculate
     */
    public function calculate(Request $request)
    {
        try {
            $request->validate([
                'items' => 'required|array|min:1',
                'items.*.produk_id' => 'required|exists:t_produk,produk_id',
                'items.*.jumlah' => 'required|integer|min:1',
                'items.*.ukuran_id' => 'required|exists:m_ukuran,ukuran_id',
                'items.*.warna_id' => 'required|exists:m_warna,warna_id',
                'kode_voucher' => 'nullable|string',
            ]);

            $items = $request->items;
            $kodeVoucher = $request->kode_voucher;

            $produkDetails = [];
            $subtotal = 0;

            foreach ($items as $item) {
                $produk = ProdukModel::with('fotoUtama')->find($item['produk_id']);

                if (!$produk) {
                    continue;
                }

                $hargaSatuan = $produk->harga_diskon ?? (float) $produk->harga;
                $jumlah = (int) $item['jumlah'];
                $totalHarga = $hargaSatuan * $jumlah;
                $subtotal += $totalHarga;

                $ukurans = UkuranModel::find($item['ukuran_id']);
                $warnas = WarnaModel::find($item['warna_id']);

                $produkDetails[] = [
                    'produk_id' => $produk->produk_id,
                    'nama_produk' => $produk->nama_produk,
                    'foto' => $produk->fotoUtama
                        ? url('storage/foto_produk/' . $produk->fotoUtama->foto_produk)
                        : null,
                    'harga_normal' => (float) $produk->harga,
                    'harga_diskon' => $produk->diskon ? (float) $produk->diskon : null,
                    'harga_satuan' => $hargaSatuan,
                    'jumlah' => $jumlah,
                    'total_harga' => $totalHarga,
                    'ukuran' => [
                        'ukuran_id' => $ukurans->ukuran_id ?? null,
                        'nama_ukuran' => $ukurans->nama_ukuran ?? null,
                    ],
                    'warna' => [
                        'warna_id' => $warnas->warna_id ?? null,
                        'nama_warna' => $warnas->nama_warna ?? null,
                        'kode_hex' => $warnas->kode_hex ?? null,
                    ],
                ];
            }

            $diskonVoucher = 0;
            $voucherInfo = null;

            if ($kodeVoucher) {
                $voucher = VoucherModel::where('kode_voucher', $kodeVoucher)->first();
                if ($voucher && $voucher->isValid($subtotal)) {
                    $diskonVoucher = $voucher->hitungPotongan($subtotal);
                    $voucherInfo = [
                        'kode_voucher' => $voucher->kode_voucher,
                        'tipe_diskon' => $voucher->tipe_diskon,
                        'nilai_diskon' => (float) $voucher->nilai_diskon,
                        'potongan' => $diskonVoucher,
                    ];
                }
            }

            $totalBayar = max(0, $subtotal - $diskonVoucher);

            return response()->json([
                'success' => true,
                'message' => 'Perhitungan berhasil',
                'data' => [
                    'items' => $produkDetails,
                    'subtotal' => $subtotal,
                    'diskon_voucher' => $diskonVoucher,
                    'voucher_info' => $voucherInfo,
                    'total_bayar' => $totalBayar,
                    'jumlah_item' => count($items),
                    'total_quantity' => collect($items)->sum('jumlah'),
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghitung totals',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Process checkout
     * POST /api/v1/checkout
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'items' => 'required|array|min:1',
                'items.*.produk_id' => 'required|exists:t_produk,produk_id',
                'items.*.jumlah' => 'required|integer|min:1',
                'items.*.ukuran_id' => 'required|exists:m_ukuran,ukuran_id',
                'items.*.warna_id' => 'required|exists:m_warna,warna_id',
                'metode_pembayaran_id' => 'required|exists:t_metode_pembayaran,metode_pembayaran_id',
                'ekspedisi_id' => 'required|exists:m_ekspedisi,ekspedisi_id',
                'kode_voucher' => 'nullable|string',
                'nama_customer' => 'required|string|max:255',
                'no_telp' => 'required|string|max:20',
                'email' => 'nullable|email|max:255',
                'alamat' => 'required|string|max:500',
            ]);

            $items = $request->items;
            $kodeVoucher = $request->kode_voucher;

            // Calculate totals
            $subtotal = 0;
            $totalQuantity = 0;

            foreach ($items as $item) {
                $produk = ProdukModel::find($item['produk_id']);
                if ($produk) {
                    $hargaSatuan = $produk->harga_diskon ?? (float) $produk->harga;
                    $subtotal += $hargaSatuan * (int) $item['jumlah'];
                    $totalQuantity += (int) $item['jumlah'];
                }
            }

            // Check and apply voucher
            $diskonVoucher = 0;
            $voucherId = null;

            if ($kodeVoucher) {
                $voucher = VoucherModel::where('kode_voucher', $kodeVoucher)->first();
                if ($voucher && $voucher->isValid($subtotal)) {
                    $diskonVoucher = $voucher->hitungPotongan($subtotal);
                    $voucherId = $voucher->voucher_id;
                }
            }

            $totalBayar = max(0, $subtotal - $diskonVoucher);

            // Check stock availability
            foreach ($items as $item) {
                $produk = ProdukModel::find($item['produk_id']);
                if ($produk && $produk->stok_produk < $item['jumlah']) {
                    return response()->json([
                        'success' => false,
                        'message' => "Stok {$produk->nama_produk} tidak mencukupi. Tersedia: {$produk->stok_produk}",
                    ], 400);
                }
            }

            // Create pembayaran
            $pembayaran = \App\Models\PembayaranModel::create([
                'metode_pembayaran_id' => $request->metode_pembayaran_id,
                'voucher_id' => $voucherId,
                'status_pembayaran' => 'menunggu pembayaran',
                'jumlah_produk' => $totalQuantity,
                'total_harga' => $totalBayar,
            ]);

            // Create transaksi
            $transaksi = \App\Models\TransaksiModel::create([
                'pembayaran_id' => $pembayaran->pembayaran_id,
                'ekspedisi_id' => $request->ekspedisi_id,
                'nama_customer' => $request->nama_customer,
                'no_telp' => $request->no_telp,
                'email' => $request->email,
                'alamat' => $request->alamat,
                'status_transaksi' => 'menunggu pembayaran',
            ]);

            // Get kode invoice that was auto-generated
            $kodeInvoice = $transaksi->kode_invoice;

            // Create detail transaksi and decrement stock
            foreach ($items as $item) {
                \App\Models\DetailTransaksiModel::create([
                    'transaksi_id' => $transaksi->transaksi_id,
                    'pembayaran_id' => $pembayaran->pembayaran_id,
                    'produk_id' => $item['produk_id'],
                    'ukuran_id' => $item['ukuran_id'],
                    'warna_id' => $item['warna_id'],
                    'jumlah' => $item['jumlah'],
                ]);

                // Decrement product stock
                $produk = ProdukModel::find($item['produk_id']);
                if ($produk) {
                    $produk->decrement('stok_produk', $item['jumlah']);
                }
            }

            // Mark voucher as used
            if ($voucherId) {
                $voucher = VoucherModel::find($voucherId);
                if ($voucher) {
                    $voucher->markAsUsed();
                }
            }

            // Get payment method info
            $metodeBayar = MetodePembayaranModel::with('metode')->find($request->metode_pembayaran_id);

            return response()->json([
                'success' => true,
                'message' => 'Checkout berhasil. Silakan lakukan pembayaran.',
                'data' => [
                    'kode_invoice' => $kodeInvoice,
                    'transaksi_id' => $transaksi->transaksi_id,
                    'pembayaran_id' => $pembayaran->pembayaran_id,
                    'total_bayar' => (float) $totalBayar,
                    'jumlah_produk' => $totalQuantity,
                    'diskon' => $diskonVoucher,
                    'status' => [
                        'pembayaran' => $pembayaran->status_pembayaran,
                        'transaksi' => $transaksi->status_transaksi,
                    ],
                    'metode_pembayaran' => [
                        'nama' => $metodeBayar->nama_pembayaran,
                        'tipe' => $metodeBayar->metode->nama_metode ?? null,
                        'kode_bayar' => $metodeBayar->kode_bayar,
                        'atas_nama' => $metodeBayar->atas_nama,
                    ],
                    'batas_waktu' => Carbon::now()->addDays(1)->toIso8601String(),
                ],
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses checkout',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}