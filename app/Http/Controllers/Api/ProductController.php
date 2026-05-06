<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProdukModel;
use App\Models\KategoriModel;
use App\Models\BahanModel;
use App\Models\FotoProdukModel;
use App\Models\WarnaModel;
use App\Models\UkuranModel;
use App\Models\WarnaProdukModel;
use App\Models\UkuranProdukModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    /**
     * Get all products with filters
     * GET /api/v1/products
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $page = $request->input('page', 1);

            // Filters
            $search = $request->input('search');
            $kategori_id = $request->input('kategori_id');
            $bahan_id = $request->input('bahan_id');
            $min_price = $request->input('min_price');
            $max_price = $request->input('max_price');
            $has_stock = $request->input('has_stock'); // boolean
            $is_best = $request->input('is_best'); // boolean
            $warna_id = $request->input('warna_id');
            $ukuran_id = $request->input('ukuran_id');
            $sort_by = $request->input('sort_by', 'terbaru');
            $sort_order = $request->input('sort_order', 'desc');

            $query = ProdukModel::with(['fotoUtama', 'kategori', 'bahan']);

            // Search
            if ($search) {
                $keywords = explode(' ', $search);
                foreach ($keywords as $word) {
                    $query->where(function ($q) use ($word) {
                        $q->where('nama_produk', 'like', '%' . $word . '%')
                          ->orWhere('deskripsi', 'like', '%' . $word . '%');
                    });
                }
            }

            // Filter by category
            if ($kategori_id) {
                $kategoriIds = is_array($kategori_id) ? $kategori_id : explode(',', $kategori_id);
                $query->whereIn('kategori_id', $kategoriIds);
            }

            // Filter by bahan
            if ($bahan_id) {
                $bahanIds = is_array($bahan_id) ? $bahan_id : explode(',', $bahan_id);
                $query->whereIn('bahan_id', $bahanIds);
            }

            // Filter by price range
            if ($min_price) {
                $query->where('harga', '>=', (float) $min_price);
            }
            if ($max_price) {
                $query->where('harga', '<=', (float) $max_price);
            }

            // Filter by stock availability
            if ($has_stock === 'true' || $has_stock === '1') {
                $query->where('stok_produk', '>', 0);
            }

            // Filter by best products
            if ($is_best === 'true' || $is_best === '1') {
                $query->where('is_best', 1);
            }

            // Filter by color
            if ($warna_id) {
                $warnaIds = is_array($warna_id) ? $warna_id : explode(',', $warna_id);
                $query->whereHas('warna', function ($q) use ($warnaIds) {
                    $q->whereIn('warna_id', $warnaIds);
                });
            }

            // Filter by size
            if ($ukuran_id) {
                $ukuranIds = is_array($ukuran_id) ? $ukuran_id : explode(',', $ukuran_id);
                $query->whereHas('ukuran', function ($q) use ($ukuranIds) {
                    $q->whereIn('ukuran_id', $ukuranIds);
                });
            }

            // Sorting
            $sortColumn = match ($sort_by) {
                'harga_termahal' => 'harga',
                'harga_termurah' => 'harga',
                'stok' => 'stok_produk',
                'nama' => 'nama_produk',
                'best' => 'is_best',
                default => 'created_at',
            };

            $sort_order = match ($sort_by) {
                'harga_termurah' => 'asc',
                default => $sort_order === 'asc' ? 'asc' : 'desc',
            };

            $query->orderBy($sortColumn, $sort_order);

            $products = $query->paginate($perPage, ['*'], 'page', $page);

            $formattedProducts = $products->map(function ($produk) {
                return [
                    'produk_id' => $produk->produk_id,
                    'nama_produk' => $produk->nama_produk,
                    'harga' => (float) $produk->harga,
                    'diskon' => $produk->diskon ? (float) $produk->diskon : null,
                    'harga_setelah_diskon' => $produk->harga_diskon,
                    'diskon_persen' => $produk->diskon_persen,
                    'stok_produk' => $produk->stok_produk,
                    'is_best' => (bool) $produk->is_best,
                    'kategori' => [
                        'kategori_id' => $produk->kategori->kategori_id ?? null,
                        'nama_kategori' => $produk->kategori->nama_kategori ?? null,
                    ],
                    'bahan' => [
                        'bahan_id' => $produk->bahan->bahan_id ?? null,
                        'nama_bahan' => $produk->bahan->nama_bahan ?? null,
                    ],
                    'foto_utama' => $produk->fotoUtama
                        ? url('storage/foto_produk/' . $produk->fotoUtama->foto_produk)
                        : null,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Data produk berhasil diambil',
                'data' => $formattedProducts,
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                    'has_more' => $products->hasMorePages(),
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data produk',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get single product detail
     * GET /api/v1/products/{id}
     */
    public function show($id)
    {
        try {
            $produk = Cache::rememberForever('api_produk_' . $id, function () use ($id) {
                return ProdukModel::with([
                    'kategori',
                    'bahan',
                    'foto' => function ($query) {
                        $query->orderBy('status_foto', 'desc');
                    },
                    'warna.warna',
                    'ukuran.ukuran',
                ])->find($id);
            });

            if (!$produk) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produk tidak ditemukan',
                ], 404);
            }

            $formattedWarna = $produk->warna->map(function ($w) {
                return [
                    'warna_id' => $w->warna->warna_id,
                    'nama_warna' => $w->warna->nama_warna,
                    'kode_hex' => $w->warna->kode_hex,
                ];
            })->unique('warna_id')->values();

            $formattedUkuran = $produk->ukuran->map(function ($u) {
                return [
                    'ukuran_id' => $u->ukuran->ukuran_id,
                    'nama_ukuran' => $u->ukuran->nama_ukuran,
                    'deskripsi' => $u->ukuran->deskripsi,
                ];
            })->unique('ukuran_id')->values();

            $formattedFoto = $produk->foto->map(function ($f) {
                return [
                    'foto_produk_id' => $f->foto_produk_id,
                    'url' => url('storage/foto_produk/' . $f->foto_produk),
                    'is_utama' => (bool) $f->status_foto,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Detail produk berhasil diambil',
                'data' => [
                    'produk_id' => $produk->produk_id,
                    'nama_produk' => $produk->nama_produk,
                    'deskripsi' => $produk->deskripsi,
                    'harga' => (float) $produk->harga,
                    'diskon' => $produk->diskon ? (float) $produk->diskon : null,
                    'harga_setelah_diskon' => $produk->harga_diskon,
                    'diskon_persen' => $produk->diskon_persen,
                    'stok_produk' => $produk->stok_produk,
                    'is_best' => (bool) $produk->is_best,
                    'tanggal_dibuat' => $produk->created_at->toIso8601String(),
                    'kategori' => [
                        'kategori_id' => $produk->kategori->kategori_id ?? null,
                        'nama_kategori' => $produk->kategori->nama_kategori ?? null,
                    ],
                    'bahan' => [
                        'bahan_id' => $produk->bahan->bahan_id ?? null,
                        'nama_bahan' => $produk->bahan->nama_bahan ?? null,
                        'deskripsi_bahan' => $produk->bahan->deskripsi ?? null,
                    ],
                    'foto' => $formattedFoto,
                    'warna' => $formattedWarna,
                    'ukuran' => $formattedUkuran,
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail produk',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get filter options for products
     * GET /api/v1/products/filters
     */
    public function filters()
    {
        try {
            $kategori = Cache::remember('api_kategori', 3600, function () {
                return KategoriModel::select('kategori_id', 'nama_kategori')->get();
            });

            $bahan = Cache::remember('api_bahan', 3600, function () {
                return BahanModel::select('bahan_id', 'nama_bahan')->get();
            });

            $warna = Cache::remember('api_warna', 3600, function () {
                return WarnaModel::select('warna_id', 'nama_warna', 'kode_hex')->get();
            });

            $ukuran = Cache::remember('api_ukuran', 3600, function () {
                return UkuranModel::select('ukuran_id', 'nama_ukuran', 'deskripsi')->get();
            });

            // Get price range
            $priceRange = Cache::remember('api_price_range', 3600, function () {
                $min = ProdukModel::min('harga');
                $max = ProdukModel::max('harga');
                return [
                    'min' => (float) ($min ?? 0),
                    'max' => (float) ($max ?? 0),
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Filter options berhasil diambil',
                'data' => [
                    'kategori' => $kategori,
                    'bahan' => $bahan,
                    'warna' => $warna,
                    'ukuran' => $ukuran,
                    'harga' => [
                        'min' => $priceRange['min'],
                        'max' => $priceRange['max'],
                    ],
                    'sort_options' => [
                        ['value' => 'terbaru', 'label' => 'Terbaru'],
                        ['value' => 'harga_termahal', 'label' => 'Harga Tertinggi'],
                        ['value' => 'harga_termurah', 'label' => 'Harga Terendah'],
                        ['value' => 'nama', 'label' => 'Nama A-Z'],
                        ['value' => 'best', 'label' => 'Best Seller'],
                    ],
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil filter options',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get best seller products
     * GET /api/v1/products/best
     */
    public function best(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 5);

            $products = ProdukModel::with(['fotoUtama', 'kategori'])
                ->where('is_best', 1)
                ->paginate($perPage);

            $formattedProducts = $products->map(function ($produk) {
                return [
                    'produk_id' => $produk->produk_id,
                    'nama_produk' => $produk->nama_produk,
                    'harga' => (float) $produk->harga,
                    'diskon' => $produk->diskon ? (float) $produk->diskon : null,
                    'harga_setelah_diskon' => $produk->harga_diskon,
                    'diskon_persen' => $produk->diskon_persen,
                    'stok_produk' => $produk->stok_produk,
                    'kategori' => $produk->kategori->nama_kategori ?? null,
                    'foto_utama' => $produk->fotoUtama
                        ? url('storage/foto_produk/' . $produk->fotoUtama->foto_produk)
                        : null,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Best products berhasil diambil',
                'data' => $formattedProducts,
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                    'has_more' => $products->hasMorePages(),
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil best products',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}