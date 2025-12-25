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
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Intervention\Image\Drivers\Gd\Driver;

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
        $sort = $request->input('sort', 'terbaru');
        $paginateLimit = $request->input('paginate', 10);

        $kategoriList = Cache::remember('kategori_list', 600, function () {
            return KategoriModel::select('kategori_id', 'nama_kategori')->get();
        });


        $produk = ProdukModel::whereHas('fotoUtama')
            ->with(['kategori', 'bahan', 'fotoUtama'])
            ->when($search, function ($query, $search) {
                $keywords = explode(' ', $search);
                foreach ($keywords as $word) {
                    $query->where('nama_produk', 'like', '%' . $word . '%');
                }
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
            ->when($sort, function ($query, $sort) {
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

        $best = ProdukModel::where('is_best', 1)->paginate(10);
        return view('admin.produk.index', compact('produk', 'best', 'kategoriList', 'bahanList', 'paginateLimit', 'title'));
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
        $manager = new ImageManager(new ImagickDriver()); // V3: Instansiasi ImageManager
        $optimizerChain = OptimizerChainFactory::create();

        // 1. Validasi Input (Tetap sama)
        $request->validate([
            'foto_utama' => 'required|mimes:jpeg,png,jpg,avif,heic,heif,webp|max:2048',
            'foto_sekunder.*' => 'nullable|mimes:jpeg,png,jpg,avif,heic,heif,webp|max:2048',
            'nama_produk' => 'required|unique:t_produk,nama_produk|regex:/^[a-zA-Z0-9\s]+$/',
            'stok_produk' => 'required|integer|min:0',
            'deskripsi' => 'required|string|max:500',
            'harga' => 'required|numeric|min:1',
            'diskon' => 'nullable|numeric|min:0|max:100',
            'kategori_id' => 'required|integer',
            'bahan_id' => 'required|integer',
            'ukuran_id' => 'required|array|min:1',
            'ukuran_id.*' => 'string|max:50',
            'warna_id' => 'required|array|min:1',
            'warna_id.*' => 'string|max:50',
        ]);

        // 2. Jalankan Logika dalam Database Transaction
        DB::transaction(function () use ($request, $optimizerChain, $manager) {

            // Buat entri Produk
            $produk = ProdukModel::create([
                'nama_produk' => $request->nama_produk,
                'deskripsi' => $request->deskripsi,
                'stok_produk' => $request->stok_produk,
                'harga' => $request->harga,
                'diskon' => $request->diskon,
                'kategori_id' => $request->kategori_id,
                'bahan_id' => $request->bahan_id,
            ]);

            // === 3. Proses Konversi dan Penyimpanan FOTO UTAMA ===
            if ($request->hasFile('foto_utama')) {
                $uploadedFile = $request->file('foto_utama');

                // 3a. Muat dan Konversi Gambar ke JPG (V3 SYNTAX: gunakan toJpeg(quality))
                $image = $manager->read($uploadedFile)->toJpeg(85);

                // 3b. Tentukan nama file baru dengan ekstensi .jpg
                $filename = Str::random(40) . '.jpg';
                $path = 'public/foto_produk/' . $filename;

                // 3c. Simpan gambar yang sudah dikonversi ke Storage (V3 SYNTAX: gunakan toString())
                Storage::put($path, $image->toString()); // <-- Diperbaiki: toString()

                // 3d. Optimasi file JPG yang baru disimpan
                $optimizerChain->optimize(storage_path('app/' . $path));

                // 3e. Simpan data foto ke database
                FotoProdukModel::create([
                    'produk_id' => $produk->produk_id,
                    'foto_produk' => $filename,
                    'status_foto' => 1
                ]);
            }

            // === 4. Proses Konversi dan Penyimpanan FOTO SEKUNDER ===
            if ($request->hasFile('foto_sekunder')) {
                foreach ($request->file('foto_sekunder') as $foto) {

                    // 4a. Muat dan Konversi Gambar ke JPG (V3 SYNTAX: gunakan toJpeg(quality))
                    $image = $manager->read($foto)->toJpeg(85);

                    // 4b. Tentukan nama file baru dengan ekstensi .jpg
                    $filename = Str::random(40) . '.jpg';
                    $path = 'public/foto_produk/' . $filename;

                    // 4c. Simpan gambar yang sudah dikonversi ke Storage (V3 SYNTAX: gunakan toString())
                    Storage::put($path, $image->toString()); // <-- Diperbaiki: toString()

                    // 4d. Optimasi file JPG yang baru disimpan
                    $optimizerChain->optimize(storage_path('app/' . $path));

                    // 4e. Simpan data foto ke database
                    FotoProdukModel::create([
                        'produk_id' => $produk->produk_id,
                        'foto_produk' => $filename,
                        'status_foto' => 0
                    ]);
                }
            }

            // ... (Logika ukuran dan warna tetap sama)
            if ($request->has('ukuran_id')) {
                foreach ($request->ukuran_id as $ukuran) {
                    UkuranProdukModel::create(['produk_id' => $produk->produk_id, 'ukuran_id' => $ukuran,]);
                }
            }
            if ($request->has('warna_id')) {
                foreach ($request->warna_id as $kode) {
                    WarnaProdukModel::create(['produk_id' => $produk->produk_id, 'warna_id' => $kode,]);
                }
            }
        });

        return redirect()->route('produk.index')->with('success', 'Produk berhasil disimpan!');
    }

    public function editBest()
    {
        $best = ProdukModel::with(['fotoUtama'])
            ->paginate(100);

        return view('admin.produk.best', compact('best'));
    }

    public function postBest(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|array|size:5',
            'produk_id.*' => 'exists:t_produk,produk_id',
        ], [
            'produk_id.required' => 'Kamu harus memilih 5 produk.',
            'produk_id.size' => 'Kamu harus memilih tepat 5 produk terbaik.',
        ]);

        ProdukModel::where('is_best', 1)->update(['is_best' => 0]);
        ProdukModel::whereIn('produk_id', $request->produk_id)->update(['is_best' => 1]);

        return redirect()->route('produk.index')->with('success', '5 produk terbaik berhasil diperbarui!');
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
        // Inisialisasi ImageManager V3 (Menggunakan Imagick karena mendukung HEIC)
        $manager = new ImageManager(new ImagickDriver());

        // Inisialisasi Optimizer Chain
        $optimizerChain = OptimizerChainFactory::create([]); // Menggunakan [] untuk menghindari error linter P1005

        // 1. Validasi Input
        $request->validate([
            // 'nullable' karena gambar bisa saja tidak diubah
            'foto_utama' => 'nullable|mimes:jpeg,png,jpg,avif,heic,heif,webp|max:2048',
            'foto_sekunder.*' => 'nullable|mimes:jpeg,png,jpg,avif,heic,heif,webp|max:2048',
            'nama_produk' => 'required|string|max:255',
            'stok_produk' => 'required|integer',
            // ... (Validasi lainnya tetap sama)
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

        // 2. Jalankan Logika dalam Database Transaction
        DB::transaction(function () use ($request, $produk, $optimizerChain, $manager) {

            // Update data produk non-media
            $produk->update($request->only([
                'nama_produk',
                'deskripsi',
                'harga',
                'diskon',
                'stok_produk',
                'kategori_id',
                'bahan_id'
            ]));

            // === 3. PROSES FOTO UTAMA ===
            if ($request->hasFile('foto_utama')) {
                $uploadedFile = $request->file('foto_utama');

                // A. Hapus Foto Lama (dari DB dan Storage)
                $oldFotoUtama = FotoProdukModel::where('produk_id', $produk->produk_id)->where('status_foto', 1)->first();
                if ($oldFotoUtama) {
                    Storage::delete('public/foto_produk/' . $oldFotoUtama->foto_produk); // Hapus dari storage
                    $oldFotoUtama->delete(); // Hapus dari DB
                }

                // B. Konversi dan Simpan Foto Baru
                $image = $manager->read($uploadedFile)->toJpeg(85);

                $filename = Str::random(40) . '.jpg';
                $path = 'public/foto_produk/' . $filename;

                Storage::put($path, $image->toString()); // Simpan yang sudah dikonversi
                $optimizerChain->optimize(storage_path('app/' . $path)); // Optimasi

                // C. Simpan entri baru ke DB
                FotoProdukModel::create([
                    'produk_id' => $produk->produk_id,
                    'foto_produk' => $filename,
                    'status_foto' => 1
                ]);
            }
            // Catatan: Jika foto sekunder diunggah, ia akan selalu ditambahkan, tidak menggantikan yang lama
            // Kecuali Anda menghapus semua foto sekunder lama di sini (logika di bawah ini hanya ADD)

            // === 4. PROSES FOTO SEKUNDER (ADD ONLY) ===
            if ($request->hasFile('foto_sekunder')) {
                foreach ($request->file('foto_sekunder') as $foto) {

                    // Konversi dan Simpan Foto Baru
                    $image = $manager->read($foto)->toJpeg(85);

                    $filename = Str::random(40) . '.jpg';
                    $path = 'public/foto_produk/' . $filename;

                    Storage::put($path, $image->toString());
                    $optimizerChain->optimize(storage_path('app/' . $path));

                    // Simpan entri baru ke DB
                    FotoProdukModel::create([
                        'produk_id' => $produk->produk_id,
                        'foto_produk' => $filename,
                        'status_foto' => 0
                    ]);
                }
            }

            // --- 5. UPDATE UKURAN DAN WARNA ---
            // Ukuran
            UkuranProdukModel::where('produk_id', $produk->produk_id)->delete();
            foreach ($request->ukuran_id as $ukuran) {
                UkuranProdukModel::create(['produk_id' => $produk->produk_id, 'ukuran_id' => $ukuran]);
            }

            // Warna
            WarnaProdukModel::where('produk_id', $produk->produk_id)->delete();
            foreach ($request->warna_id as $warnaKode) {
                WarnaProdukModel::create(['produk_id' => $produk->produk_id, 'warna_id' => $warnaKode]);
            }

            // Hapus Cache
            Cache::forget('produk_' . $produk->produk_id);
            Cache::forget('edit_produk_' . $produk->produk_id);
        });

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui!');
    }
    
    public function destroy($id)
    {
        try {
            $produk = ProdukModel::with(['foto'])->findOrFail($id);

            DB::beginTransaction();

            // Hapus relasi dulu
            $produk->foto()->delete();
            $produk->warna()->delete();
            $produk->ukuran()->delete();

            // Hapus produk
            $produk->delete();

            DB::commit();

            // Setelah commit berhasil, hapus file fisik
            foreach ($produk->foto as $foto) {
                if ($foto->foto_produk && Storage::exists('public/foto_produk/' . $foto->foto_produk)) {
                    Storage::delete('public/foto_produk/' . $foto->foto_produk);
                }
            }

            // Hapus cache
            Cache::forget('produk_' . $id);
            Cache::forget('edit_produk_' . $id);

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dihapus',
                'id' => $id
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak bisa dihapus karena masih digunakan pada detail pesanan.'
            ], 400);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
