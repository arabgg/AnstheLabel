<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TransaksiModel;
use App\Models\PembayaranModel;
use App\Models\DetailTransaksiModel;
use App\Models\ProdukModel;
use App\Models\FotoProdukModel;
use App\Models\MetodePembayaranModel;
use App\Models\MetodeModel;
use App\Models\EkspedisiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TransactionController extends Controller
{
    /**
     * Get transaction by invoice code
     * GET /api/v1/transactions/{kode_invoice}
     */
    public function show($kode_invoice)
    {
        try {
            $transaksi = TransaksiModel::with([
                'pembayaran.metode.metode',
                'ekspedisi',
                'detail.produk.fotoUtama',
                'detail.ukuran',
                'detail.warna',
            ])->where('kode_invoice', $kode_invoice)->first();

            if (!$transaksi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi tidak ditemukan',
                ], 404);
            }

            $formattedDetail = $transaksi->detail->map(function ($d) {
                return [
                    'detail_id' => $d->detail_transaksi_id,
                    'produk' => [
                        'produk_id' => $d->produk->produk_id ?? null,
                        'nama_produk' => $d->produk->nama_produk ?? null,
                        'harga' => (float) ($d->produk->harga ?? 0),
                        'foto' => $d->produk->fotoUtama
                            ? url('storage/foto_produk/' . $d->produk->fotoUtama->foto_produk)
                            : null,
                    ],
                    'ukuran' => [
                        'ukuran_id' => $d->ukuran->ukuran_id ?? null,
                        'nama_ukuran' => $d->ukuran->nama_ukuran ?? null,
                    ],
                    'warna' => [
                        'warna_id' => $d->warna->warna_id ?? null,
                        'nama_warna' => $d->warna->nama_warna ?? null,
                        'kode_hex' => $d->warna->kode_hex ?? null,
                    ],
                    'jumlah' => $d->jumlah,
                    'subtotal' => (float) ($d->produk->harga ?? 0) * $d->jumlah,
                ];
            });

            // Get metode pembayaran dengan relasi yang benar
            $metodeBayar = $transaksi->pembayaran->metode ?? null;

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil ditemukan',
                'data' => [
                    'kode_invoice' => $transaksi->kode_invoice,
                    'transaksi_id' => $transaksi->transaksi_id,
                    'tanggal_pesan' => $transaksi->created_at->toIso8601String(),
                    'status' => [
                        'pembayaran' => $transaksi->pembayaran->status_pembayaran ?? null,
                        'transaksi' => $transaksi->status_transaksi,
                    ],
                    'customer' => [
                        'nama' => $transaksi->nama_customer,
                        'no_telp' => $transaksi->no_telp,
                        'email' => $transaksi->email,
                        'alamat' => $transaksi->alamat,
                    ],
                    'ekspedisi' => $transaksi->ekspedisi ? [
                        'ekspedisi_id' => $transaksi->ekspedisi->ekspedisi_id,
                        'nama_ekspedisi' => $transaksi->ekspedisi->nama_ekspedisi,
                    ] : null,
                    'pembayaran' => [
                        'jumlah_produk' => $transaksi->pembayaran->jumlah_produk ?? 0,
                        'total_harga' => (float) ($transaksi->pembayaran->total_harga ?? 0),
                        'bukti_pembayaran' => $transaksi->pembayaran->bukti_pembayaran
                            ? url('storage/bukti_pembayaran/' . $transaksi->pembayaran->bukti_pembayaran)
                            : null,
                        'metode' => $metodeBayar ? [
                            'metode_pembayaran_id' => $metodeBayar->metode_pembayaran_id,
                            'nama_pembayaran' => $metodeBayar->nama_pembayaran,
                            'kode_bayar' => $metodeBayar->kode_bayar,
                            'atas_nama' => $metodeBayar->atas_nama,
                            'tipe' => $metodeBayar->metode->nama_metode ?? null,
                        ] : null,
                    ],
                    'items' => $formattedDetail,
                    'timeline' => $this->buildTimeline($transaksi),
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data transaksi',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Upload payment proof
     * POST /api/v1/transactions/{kode_invoice}/upload-payment
     */
   public function uploadPayment(Request $request, $kode_invoice)
    {
        try {
            $request->validate([
                'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            $transaksi = TransaksiModel::where('kode_invoice', $kode_invoice)->first();

            if (!$transaksi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi tidak ditemukan',
                ], 404);
            }

            if ($transaksi->pembayaran->status_pembayaran !== 'menunggu pembayaran') {
                return response()->json([
                    'success' => false,
                    'message' => 'Pembayaran sudah diproses atau tidak dapat diupload',
                ], 400);
            }

            // Delete old payment proof if exists
            if ($transaksi->pembayaran->bukti_pembayaran) {
                Storage::delete('public/bukti/' . $transaksi->pembayaran->bukti_pembayaran);
            }

            // Store new payment proof
            $file = $request->file('bukti_pembayaran');
            $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $path = 'public/bukti/' . $filename;
            Storage::put($path, file_get_contents($file));

            // Update pembayaran
            $transaksi->pembayaran->update([
                'bukti_pembayaran' => $filename,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Bukti pembayaran berhasil diupload',
                'data' => [
                    'kode_invoice' => $kode_invoice,
                    'bukti_pembayaran_url' => url('storage/bukti/' . $filename),
                    'status_pembayaran' => $transaksi->pembayaran->fresh()->status_pembayaran,
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal upload bukti pembayaran',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get transaction history for a customer
     * GET /api/v1/transactions
     */
    public function index(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
            ]);

            $email = $request->email;
            $perPage = $request->input('per_page', 10);
            $status = $request->input('status');

            $query = TransaksiModel::with([
                'pembayaran',
                'detail.produk.fotoUtama',
            ])->where('email', $email);

            if ($status) {
                $query->where('status_transaksi', $status);
            }

            $transactions = $query->orderBy('created_at', 'desc')->paginate($perPage);

            $formattedTransactions = $transactions->map(function ($t) {
                // Get first item for preview
                $firstItem = $t->detail->first();
                $previewProduct = $firstItem?->produk;
                $previewImage = $previewProduct?->fotoUtama
                    ? url('storage/foto_produk/' . $previewProduct->fotoUtama->foto_produk)
                    : null;

                return [
                    'kode_invoice' => $t->kode_invoice,
                    'transaksi_id' => $t->transaksi_id,
                    'tanggal_pesan' => $t->created_at->toIso8601String(),
                    'status' => [
                        'pembayaran' => $t->pembayaran->status_pembayaran ?? null,
                        'transaksi' => $t->status_transaksi,
                    ],
                    'jumlah_produk' => $t->detail->count(),
                    'total_bayar' => (float) ($t->pembayaran->total_harga ?? 0),
                    'preview' => [
                        'nama_produk' => $previewProduct->nama_produk ?? null,
                        'foto' => $previewImage,
                    ],
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Riwayat transaksi berhasil diambil',
                'data' => $formattedTransactions,
                'pagination' => [
                    'current_page' => $transactions->currentPage(),
                    'last_page' => $transactions->lastPage(),
                    'per_page' => $transactions->perPage(),
                    'total' => $transactions->total(),
                    'has_more' => $transactions->hasMorePages(),
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil riwayat transaksi',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Cancel transaction
     * POST /api/v1/transactions/{kode_invoice}/cancel
     */
    public function cancel(Request $request, $kode_invoice)
    {
        try {
            $transaksi = TransaksiModel::where('kode_invoice', $kode_invoice)->first();

            if (!$transaksi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi tidak ditemukan',
                ], 404);
            }

            // Can only cancel if status is 'menunggu pembayaran'
            if ($transaksi->status_transaksi !== 'menunggu pembayaran') {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi tidak dapat dibatalkan. Status saat ini: ' . $transaksi->status_transaksi,
                ], 400);
            }

            $transaksi->update([
                'status_transaksi' => 'batal',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dibatalkan',
                'data' => [
                    'kode_invoice' => $kode_invoice,
                    'status_transaksi' => 'batal',
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membatalkan transaksi',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Build timeline/status history for transaction
     */
    private function buildTimeline($transaksi)
    {
        $timeline = [];
        $created = $transaksi->created_at;

        // Order placed
        $timeline[] = [
            'status' => 'dipesan',
            'label' => 'Pesanan Dibuat',
            'tanggal' => $created->toIso8601String(),
            'selesai' => true,
        ];

        // Payment waiting
        $timeline[] = [
            'status' => 'menunggu_pembayaran',
            'label' => 'Menunggu Pembayaran',
            'tanggal' => $created->toIso8601String(),
            'selesai' => $transaksi->pembayaran->status_pembayaran !== 'menunggu pembayaran',
        ];

        // Payment confirmed
        if ($transaksi->pembayaran->bukti_pembayaran) {
            $timeline[] = [
                'status' => 'pembayaran_dikonfirmasi',
                'label' => 'Pembayaran Dikonfirmasi',
                'tanggal' => $transaksi->pembayaran->updated_at->toIso8601String(),
                'selesai' => in_array($transaksi->status_transaksi, ['dikemas', 'dikirim', 'selesai']),
            ];
        }

        // Being packed
        $timeline[] = [
            'status' => 'dikemas',
            'label' => 'Sedang Dikemas',
            'tanggal' => null,
            'selesai' => in_array($transaksi->status_transaksi, ['dikirim', 'selesai']),
        ];

        // Shipped
        $timeline[] = [
            'status' => 'dikirim',
            'label' => 'Sedang Dikirim',
            'tanggal' => null,
            'selesai' => $transaksi->status_transaksi === 'selesai',
        ];

        // Completed
        $timeline[] = [
            'status' => 'selesai',
            'label' => 'Pesanan Selesai',
            'tanggal' => $transaksi->status_transaksi === 'selesai'
                ? $transaksi->updated_at->toIso8601String()
                : null,
            'selesai' => $transaksi->status_transaksi === 'selesai',
        ];

        return $timeline;
    }
}