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
use Illuminate\Support\Facades\Validator;

class ProdukController extends Controller
{
    public function index(Request $request)
    {

        // Ambil filter dari request
        $search = $request->input('search');
        $kategoriFilter = $request->input('kategori');

        $produk = ProdukModel::whereHas('fotoUtama') // hanya produk yang punya foto utama
            ->with(['kategori', 'bahan', 'fotoUtama'])
            ->when($kategoriFilter, function ($query, $kategoriFilter) {
                return $query->whereHas('kategori', function ($q) use ($kategoriFilter) {
                    $q->where('kategori_id', $kategoriFilter);
                });
            })
            ->when(request('search'), function ($query) {
                $query->where('nama_produk', 'like', '%' . request('search') . '%');
            })
            ->get();

        // Ambil data filter
        $kategoriList = KategoriModel::select('kategori_id', 'nama_kategori')->get();

        return view('produk.index', compact('produk', 'kategoriList'));
    }

    public function show($id)
    {
        $produk = ProdukModel::with([
            'fotoUtama',
            'foto',
            'ukuran.ukuran',
            'warna.warna'
        ])->findOrFail($id);

        return view('produk.show', compact('produk'));
    }


    public function create()
    {
        $kategori = KategoriModel::select('kategori_id', 'nama_kategori')->get();
        $bahan = BahanModel::select('bahan_id', 'nama_bahan')->get();
        $ukuran = UkuranModel::select('ukuran_id', 'nama_ukuran')->get();
        $warna = WarnaModel::select('warna_id', 'kode_hex')->get();
        return view('produk.create', compact('kategori', 'bahan', 'ukuran', 'warna'));
    }

    public function store(Request $request)
    {
        $optimizerChain = OptimizerChainFactory::create();

        $request->validate([
            'foto_utama' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'foto_sekunder.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|string',
            'diskon' => 'nullable|string',
            'kategori_id' => 'required|integer',
            'bahan_id' => 'required|integer',
            'ukuran_id' => 'required|array|min:1',
            'ukuran_id.*' => 'string|max:50',
            'warna_id' => 'required|array|min:1',
            'warna_id.*' => 'string|max:50',
        ], [
            'foto_utama.image' => 'Foto utama harus berupa gambar.',
            'foto_utama.mimes' => 'Format foto utama hanya boleh jpeg, png, atau jpg.',
            'foto_utama.max' => 'Ukuran foto utama maksimal 2 MB.',

            'foto_sekunder.*.image' => 'Foto sekunder harus berupa gambar.',
            'foto_sekunder.*.mimes' => 'Format foto sekunder hanya boleh jpeg, png, atau jpg.',
            'foto_sekunder.*.max' => 'Ukuran foto sekunder maksimal 2 MB.',

            'nama_produk.required' => 'Kolom Nama Produk wajib diisi.',
            'nama_produk.string' => 'Nama Produk harus berupa teks.',
            'nama_produk.max' => 'Nama Produk maksimal 255 karakter.',

            'deskripsi.required' => 'Kolom Deskripsi wajib diisi.',
            'deskripsi.string' => 'Deskripsi harus berupa teks.',

            'harga.required' => 'Kolom Harga wajib diisi.',
            'harga.string' => 'Harga harus berupa teks.',

            'diskon.required' => 'Kolom Diskon wajib diisi.',
            'diskon.string' => 'Diskon harus berupa teks.',

            'kategori_id.required' => 'Kolom Kategori wajib dipilih.',
            'kategori_id.integer' => 'Kategori tidak valid.',

            'bahan_id.required' => 'Kolom Bahan wajib dipilih.',
            'bahan_id.integer' => 'Bahan tidak valid.',

            'ukuran_id.required' => 'Setidaknya pilih satu Ukuran.',
            'ukuran_id.array' => 'Ukuran tidak valid.',
            'ukuran_id.*.string' => 'Ukuran harus berupa teks.',
            'ukuran_id.*.max' => 'Ukuran maksimal 50 karakter.',

            'warna_id.required' => 'Setidaknya pilih satu Warna.',
            'warna_id.array' => 'Warna tidak valid.',
            'warna_id.*.string' => 'Warna harus berupa teks.',
            'warna_id.*.max' => 'Warna maksimal 50 karakter.',
        ]);

        // Simpan produk
        $produk = ProdukModel::create([
            'nama_produk' => $request->nama_produk,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'diskon' => $request->diskon,
            'kategori_id' => $request->kategori_id,
            'bahan_id' => $request->bahan_id,
        ]);

        // Foto utama
        if ($request->hasFile('foto_utama')) {
            $fotoUtama = $request->file('foto_utama');
            $filename = time() . '_' . $fotoUtama->getClientOriginalName();
            $path = public_path('storage/foto_produk'); // Path tujuan langsung di folder public
            $fotoUtama->move($path, $filename); // Memindahkan file ke path tujuan

            $optimizerChain->optimize($path . '/' . $filename);

            FotoProdukModel::create([
                'produk_id' => $produk->produk_id,
                'foto_produk' => $filename,
                'status_foto' => 1 // 1 = foto utama
            ]);
        }

        // Foto sekunder
        if ($request->hasFile('foto_sekunder')) {
            foreach ($request->file('foto_sekunder') as $foto) {
                $filename = time() . '_' . $foto->getClientOriginalName();
                $path = public_path('storage/foto_produk'); // Path tujuan di folder public
                $foto->move($path, $filename); // Pindahkan file

                $optimizerChain->optimize($path . '/' . $filename);

                FotoProdukModel::create([
                    'produk_id' => $produk->produk_id,
                    'foto_produk' =>  $filename,
                    'status_foto' => 0 // 0 = foto biasa
                ]);
            }
        }

        // Ukuran
        if ($request->has('ukuran_id')) {
            foreach ($request->ukuran_id as $ukuran) {
                UkuranProdukModel::create([
                    'produk_id' => $produk->produk_id,
                    'ukuran_id' => $ukuran,
                ]);
            }
        }

        // Warna
        if ($request->has('warna_id')) {
            foreach ($request->warna_id as $kode) {
                WarnaProdukModel::create([
                    'produk_id' => $produk->produk_id,
                    'warna_id' => $kode,
                ]);
            }
        }

        return redirect()->route('produk.index')->with('success', 'Produk berhasil disimpan!');
    }

    public function edit($id)
    {
        $produk = ProdukModel::with(['warna', 'ukuran', 'foto'])->findOrFail($id);
        $kategori = KategoriModel::select('kategori_id', 'nama_kategori')->get();
        $bahan = BahanModel::select('bahan_id', 'nama_bahan')->get();
        $ukuran = UkuranModel::select('ukuran_id', 'nama_ukuran')->get();
        $warna = WarnaModel::select('warna_id', 'kode_hex')->get();

        return view('produk.edit', compact('produk', 'kategori', 'bahan', 'ukuran', 'warna'));
    }

    public function update(Request $request, $id)
    {
        $optimizerChain = OptimizerChainFactory::create();

        $request->validate([
            'foto_utama' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_sekunder.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|string',
            'diskon' => 'nullable|string',
            'kategori_id' => 'required|integer',
            'bahan_id' => 'required|integer',
            'ukuran_id' => 'required|array|min:1',
            'ukuran_id.*' => 'string|max:50',
            'warna_id' => 'required|array|min:1',
            'warna_id.*' => 'string|max:50',
        ], [
            'foto_utama.image' => 'Foto utama harus berupa gambar.',
            'foto_utama.mimes' => 'Format foto utama hanya boleh jpeg, png, atau jpg.',
            'foto_utama.max' => 'Ukuran foto utama maksimal 2 MB.',

            'foto_sekunder.*.image' => 'Foto sekunder harus berupa gambar.',
            'foto_sekunder.*.mimes' => 'Format foto sekunder hanya boleh jpeg, png, atau jpg.',
            'foto_sekunder.*.max' => 'Ukuran foto sekunder maksimal 2 MB.',

            'nama_produk.required' => 'Kolom Nama Produk wajib diisi.',
            'nama_produk.string' => 'Nama Produk harus berupa teks.',
            'nama_produk.max' => 'Nama Produk maksimal 255 karakter.',

            'deskripsi.required' => 'Kolom Deskripsi wajib diisi.',
            'deskripsi.string' => 'Deskripsi harus berupa teks.',

            'harga.required' => 'Kolom Harga wajib diisi.',
            'harga.string' => 'Harga harus berupa teks.',

            'diskon.required' => 'Kolom Diskon wajib diisi.',
            'diskon.string' => 'Diskon harus berupa teks.',

            'kategori_id.required' => 'Kolom Kategori wajib dipilih.',
            'kategori_id.integer' => 'Kategori tidak valid.',

            'bahan_id.required' => 'Kolom Bahan wajib dipilih.',
            'bahan_id.integer' => 'Bahan tidak valid.',

            'ukuran_id.required' => 'Setidaknya pilih satu Ukuran.',
            'ukuran_id.array' => 'Ukuran tidak valid.',
            'ukuran_id.*.string' => 'Ukuran harus berupa teks.',
            'ukuran_id.*.max' => 'Ukuran maksimal 50 karakter.',

            'warna_id.required' => 'Setidaknya pilih satu Warna.',
            'warna_id.array' => 'Warna tidak valid.',
            'warna_id.*.string' => 'Warna harus berupa teks.',
            'warna_id.*.max' => 'Warna maksimal 50 karakter.',
        ]);

        $produk = ProdukModel::findOrFail($id);

        // Update data produk
        $produk->update($request->only(['nama_produk', 'deskripsi', 'harga', 'diskon', 'kategori_id', 'bahan_id']));

        // Update foto utama
        if ($request->hasFile('foto_utama')) {
            // Hapus foto utama lama
            FotoProdukModel::where('produk_id', $id)->where('status_foto', 1)->delete();

            $fotoUtama = $request->file('foto_utama');
            $filename = time() . '_' . $fotoUtama->getClientOriginalName();
            $path = public_path('storage/foto_produk');
            $fotoUtama->move($path, $filename);

            // Optimasi gambar
            $optimizerChain->optimize($path . '/' . $filename);

            FotoProdukModel::create([
                'produk_id' => $id,
                'foto_produk' => $filename,
                'status_foto' => 1
            ]);
        }

        // Upload foto sekunder
        if ($request->hasFile('foto_sekunder')) {
            foreach ($request->file('foto_sekunder') as $foto) {
                $filename = time() . '_' . $foto->getClientOriginalName();
                $path = public_path('storage/foto_produk'); // Path tujuan langsung di folder public
                $foto->move($path, $filename);

                $optimizerChain->optimize($path . '/' . $filename);

                FotoProdukModel::create([
                    'produk_id' => $id,
                    'foto_produk' => $filename,
                    'status_foto' => 0
                ]);
            }
        }

        // Update ukuran
        UkuranProdukModel::where('produk_id', $id)->delete();
        foreach ($request->ukuran_id as $ukuran) {
            UkuranProdukModel::create([
                'produk_id' => $id,
                'ukuran_id' => $ukuran
            ]);
        }

        // Update warna
        WarnaProdukModel::where('produk_id', $id)->delete();
        foreach ($request->warna_id as $warnaKode) {
            WarnaProdukModel::create([
                'produk_id' => $id,
                'warna_id' => $warnaKode
            ]);
        }

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $produk = ProdukModel::findOrFail($id);

        $produk->foto()->delete();
        $produk->warnaProduk()->delete();
        $produk->ukuran()->delete();
        $produk->toko()->delete();

        $produk->delete();

        return redirect('/produk')->with('success', 'Produk berhasil diperbarui');
    }
}
