<?php

namespace App\Http\Controllers;

use App\Models\KategoriModel;
use App\Models\BahanModel;
use App\Models\ProdukModel;
use App\Models\WarnaProdukModel;
use App\Models\WarnaModel;
use App\Models\UkuranModel;
use App\Models\UkuranProdukModel;
use App\Models\FotoProdukModel;
use Illuminate\Http\Request;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ProdukController extends Controller
{

    public function index(Request $request)
    {
        $title = "List Produk";
        $search = $request->input('search');
        $kategoriFilter = $request->input('kategori');
        $stokMin = $request->input('stok_min');
        $hargaMax = $request->input('harga_max');
        $bahanFilter = $request->input('bahan');
        $sort = $request->input('sort');
        $paginateLimit = $request->input('paginate', 10);

        $kategoriList = Cache::remember('kategori_list', 600, function () {
            return KategoriModel::select('kategori_id', 'nama_kategori')->get();
        });

        $produk = ProdukModel::whereHas('fotoUtama')
            ->with(['kategori', 'bahan', 'fotoUtama'])
            ->when($search, function ($query, $search) {
                $query->where('nama_produk', 'like', '%' . $search . '%');
            })
            ->when($kategoriFilter, function ($query, $kategoriFilter) {
                return $query->whereHas('kategori', function ($q) use ($kategoriFilter) {
                    $q->where('kategori_id', $kategoriFilter);
                });
            })
            ->when($stokMin, function ($query, $stokMin) {
                $query->where('stok_produk', '>=', $stokMin);
            })
            ->when($hargaMax, function ($query, $hargaMax) {
                $query->where('harga', '<=', $hargaMax);
            })
            ->when($bahanFilter, function ($query, $bahanFilter) {
                $query->where('bahan_id', $bahanFilter);
            })
            ->when($sort, function ($query, $sort) { // Logika sorting
                if ($sort === 'terbaru') {
                    $query->orderBy('created_at', 'desc');
                } elseif ($sort === 'terlama') {
                    $query->orderBy('created_at', 'asc');
                } elseif ($sort === 'terupdate') {
                    $query->orderBy('updated_at', 'desc');
                } elseif ($sort === 'stok_terbanyak') {
                    $query->orderBy('stok_produk', 'desc');
                } elseif ($sort === 'stok_tersedikit') {
                    $query->orderBy('stok_produk', 'asc');
                } elseif ($sort === 'harga_termahal') {
                    $query->orderBy('harga', 'desc');
                } elseif ($sort === 'harga_termurah') {
                    $query->orderBy('harga', 'asc');
                }
            })
            ->paginate($paginateLimit)
            ->withQueryString();

        $bahanList = Cache::remember('bahan_list', 600, function () {
            return BahanModel::select('bahan_id', 'nama_bahan')->get();
        });
        
        return view('admin.produk.index', compact('produk', 'kategoriList', 'bahanList', 'paginateLimit', 'title'));
    }

    public function show($id)
    {
        $title = "Detail Produk";
        $produk = Cache::rememberForever('produk_' . $id, function () use ($id) {
            return ProdukModel::with([
                'bahan',
                'fotoUtama',
                'foto',
                'ukuran.ukuran',
                'warna.warna'
            ])->findOrFail($id);
        });

        return view('admin.produk.show', compact('produk', 'title'));
    }

    public function create()
    {
        $title = "Tambah Produk";
        $kategori = Cache::remember('create_form_kategori', 600, function () {
            return KategoriModel::select('kategori_id', 'nama_kategori')->get();
        });
        $bahan = Cache::remember('create_form_bahan', 600, function () {
            return BahanModel::select('bahan_id', 'nama_bahan')->get();
        });
        $ukuran = Cache::remember('create_form_ukuran', 600, function () {
            return UkuranModel::select('ukuran_id', 'nama_ukuran')->get();
        });
        $warna = Cache::remember('create_form_warna', 600, function () {
            return WarnaModel::select('warna_id', 'kode_hex', 'nama_warna')->get();
        });

        return view('admin.produk.create', compact('kategori', 'bahan', 'ukuran', 'warna', 'title'));
    }

    public function store(Request $request)
    {
        $optimizerChain = OptimizerChainFactory::create();

        $request->validate([
            'foto_utama' => 'required|image|mimes:jpeg,png,jpg,avif|max:2048',
            'foto_sekunder.*' => 'nullable|image|mimes:jpeg,png,jpg,avif|max:2048',
            'nama_produk' => 'required|string|max:255',
            'is_best' => 'nullable|boolean',
            'stok_produk' => 'required|integer',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'diskon' => 'nullable|numeric|min:0',
            'kategori_id' => 'required|integer',
            'bahan_id' => 'required|integer',
            'ukuran_id' => 'required|array|min:1',
            'ukuran_id.*' => 'string|max:50',
            'warna_id' => 'required|array|min:1',
            'warna_id.*' => 'string|max:50',
            'kategori_manual' => 'nullable|string|max:255',
            'bahan_manual' => 'nullable|string|max:255',
            'ukuran_manual' => 'nullable|array',
            'ukuran_manual.*' => 'string|max:50',
            'warna_manual' => 'nullable|array',
            'warna_manual.*' => 'string|max:50',
        ]);

        DB::transaction(function () use ($request, $optimizerChain) {
            if ($request->filled('kategori_manual')) {
                $k = KategoriModel::firstOrCreate(
                    ['nama_kategori' => $request->kategori_manual]
                );
                $request->merge(['kategori_id' => $k->kategori_id]);
                Cache::forget('kategori_list');
                Cache::forget('create_form_kategori');
            }

            if ($request->filled('bahan_manual')) {
                $b = BahanModel::firstOrCreate(
                    ['nama_bahan' => $request->bahan_manual]
                );
                $request->merge(['bahan_id' => $b->bahan_id]);
                Cache::forget('create_form_bahan');
            }

            $produk = ProdukModel::create([
                'nama_produk' => $request->nama_produk,
                'is_best' => $request->is_best,
                'deskripsi' => $request->deskripsi,
                'stok_produk' => $request->stok_produk,
                'harga' => $request->harga,
                'diskon' => $request->diskon,
                'kategori_id' => $request->kategori_id,
                'bahan_id' => $request->bahan_id,
            ]);

            if ($request->hasFile('foto_utama')) {
                $fotoUtama = $request->file('foto_utama');
                $filename = $fotoUtama->hashName();

                // Simpan ke storage/app/public/foto_produk
                $fotoUtama->storeAs('public/foto_produk', $filename);

                // Optimasi file
                $optimizerChain->optimize(storage_path('app/public/foto_produk/' . $filename));

                FotoProdukModel::create([
                    'produk_id' => $produk->produk_id,
                    'foto_produk' => $filename,
                    'status_foto' => 1
                ]);
            }

            if ($request->hasFile('foto_sekunder')) {
                foreach ($request->file('foto_sekunder') as $foto) {
                    $filename = $foto->hashName();
                    $foto->storeAs('public/foto_produk', $filename);
                    $optimizerChain->optimize(storage_path('app/public/foto_produk/' . $filename));

                    FotoProdukModel::create([
                        'produk_id' => $produk->produk_id,
                        'foto_produk' => $filename,
                        'status_foto' => 0
                    ]);
                }
            }

            if ($request->has('ukuran_id')) {
                foreach ($request->ukuran_id as $ukuran) {
                    UkuranProdukModel::create([
                        'produk_id' => $produk->produk_id,
                        'ukuran_id' => $ukuran,
                    ]);
                }
            }
            if ($request->has('ukuran_manual')) {
                foreach ($request->ukuran_manual as $namaUkuranBaru) {
                    $u = UkuranModel::firstOrCreate(['nama_ukuran' => $namaUkuranBaru]);
                    UkuranProdukModel::firstOrCreate([
                        'produk_id' => $produk->produk_id,
                        'ukuran_id' => $u->ukuran_id,
                    ]);
                    Cache::forget('create_form_ukuran');
                }
            }

            if ($request->has('warna_id')) {
                foreach ($request->warna_id as $kode) {
                    WarnaProdukModel::create([
                        'produk_id' => $produk->produk_id,
                        'warna_id' => $kode,
                    ]);
                }
            }
            if ($request->has('warna_manual')) {
                foreach ($request->warna_manual as $hex) {
                    $w = WarnaModel::firstOrCreate(
                        ['kode_hex' => strtoupper($hex)],
                        ['nama_warna' => strtoupper($hex)]
                    );
                    WarnaProdukModel::firstOrCreate([
                        'produk_id' => $produk->produk_id,
                        'warna_id' => $w->warna_id,
                    ]);
                    Cache::forget('create_form_warna');
                }
            }
        });

        return redirect()->route('produk.index')->with('success', 'Produk berhasil disimpan!');
    }

    public function edit($id)
    {
        $title = "Edit Produk";
        $produk = Cache::rememberForever('edit_produk_' . $id, function () use ($id) {
            return ProdukModel::with(['warna', 'ukuran', 'foto'])->findOrFail($id);
        });

        $kategori = Cache::remember('create_form_kategori', 600, function () {
            return KategoriModel::select('kategori_id', 'nama_kategori')->get();
        });
        $bahan = Cache::remember('create_form_bahan', 600, function () {
            return BahanModel::select('bahan_id', 'nama_bahan')->get();
        });
        $ukuran = Cache::remember('create_form_ukuran', 600, function () {
            return UkuranModel::select('ukuran_id', 'nama_ukuran')->get();
        });
        $warna = Cache::remember('create_form_warna', 600, function () {
            return WarnaModel::select('warna_id', 'kode_hex', 'nama_warna')->get();
        });

        return view('admin.produk.edit', compact('produk', 'kategori', 'bahan', 'ukuran', 'warna', 'title'));
    }

    public function update(Request $request, $id)
    {
        $optimizerChain = OptimizerChainFactory::create();

        $request->validate([
            'foto_utama' => 'nullable|image|mimes:jpeg,png,jpg,avif|max:2048',
            'foto_sekunder.*' => 'nullable|image|mimes:jpeg,png,jpg,avif|max:2048',
            'nama_produk' => 'required|string|max:255',
            'is_best' => 'nullable|boolean',
            'stok_produk' => 'required|integer',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'diskon' => 'nullable|numeric|min:0',
            'kategori_id' => 'required|integer',
            'bahan_id' => 'required|integer',
            'ukuran_id' => 'required|array|min:1',
            'ukuran_id.*' => 'string|max:50',
            'warna_id' => 'required|array|min:1',
            'warna_id.*' => 'string|max:50',
        ]);

        $produk = ProdukModel::findOrFail($id);

        DB::transaction(function () use ($request, $produk, $optimizerChain) {

            $produk->update($request->only(['nama_produk', 'deskripsi', 'harga', 'diskon', 'stok_produk', 'is_best', 'kategori_id', 'bahan_id']));

            if ($request->hasFile('foto_utama')) {
                FotoProdukModel::where('produk_id', $produk->produk_id)->where('status_foto', 1)->delete();

                $fotoUtama = $request->file('foto_utama');
                $filename = $fotoUtama->hashName();
                $fotoUtama->storeAs('public/foto_produk', $filename);
                $optimizerChain->optimize(storage_path('app/public/foto_produk/' . $filename));

                FotoProdukModel::create([
                    'produk_id' => $produk->produk_id,
                    'foto_produk' => $filename,
                    'status_foto' => 1
                ]);
            }

            if ($request->hasFile('foto_sekunder')) {
                foreach ($request->file('foto_sekunder') as $foto) {
                    $filename = $foto->hashName();
                    $foto->storeAs('public/foto_produk', $filename);
                    $optimizerChain->optimize(storage_path('app/public/foto_produk/' . $filename));

                    FotoProdukModel::create([
                        'produk_id' => $produk->produk_id,
                        'foto_produk' => $filename,
                        'status_foto' => 0
                    ]);
                }
            }

            UkuranProdukModel::where('produk_id', $produk->produk_id)->delete();
            foreach ($request->ukuran_id as $ukuran) {
                UkuranProdukModel::create([
                    'produk_id' => $produk->produk_id,
                    'ukuran_id' => $ukuran
                ]);
            }

            WarnaProdukModel::where('produk_id', $produk->produk_id)->delete();
            foreach ($request->warna_id as $warnaKode) {
                WarnaProdukModel::create([
                    'produk_id' => $produk->produk_id,
                    'warna_id' => $warnaKode
                ]);
            }
            Cache::forget('produk_' . $produk->produk_id);
            Cache::forget('edit_produk_' . $produk->produk_id);
        });

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $produk = ProdukModel::findOrFail($id);

            $fotos = $produk->foto;

            foreach ($fotos as $foto) {
                Storage::delete('public/foto_produk/' . $foto->foto_produk);
            }

            $produk->foto()->delete();
            $produk->warna()->delete();
            $produk->ukuran()->delete();

            $produk->delete();

            Cache::forget('produk_' . $id);
            Cache::forget('edit_produk_' . $id);
        });

        return redirect()->route('produk.index')->with('success', 'Produk berhasil disimpan!');
    }
}
