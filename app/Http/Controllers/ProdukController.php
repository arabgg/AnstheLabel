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
        $kategoriList = KategoriModel::all();

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
        $kategori = KategoriModel::all();
        $bahan = BahanModel::all();
        $ukuran = UkuranModel::all();
        $warna = WarnaModel::all();
        return view('produk.create', compact('kategori', 'bahan', 'ukuran', 'warna'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'foto_utama' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'foto_sekunder.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|string',
            'diskon' => 'required|string',
            'kategori_id' => 'required|integer',
            'bahan_id' => 'required|integer',
            'ukuran_id' => 'required|array|min:1',
            'ukuran_id.*' => 'string|max:50',
            'warna_id' => 'required|array|min:1',
            'warna_id.*' => 'string|max:50',
        ], [
            'foto_utama.required' => 'Foto utama wajib diunggah.',
            'foto_utama.image' => 'File harus berupa gambar.',
            'foto_utama.mimes' => 'Format gambar hanya boleh jpeg, png, atau jpg.',
            'foto_utama.max' => 'Ukuran gambar maksimal 2 MB.',
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

                // Pindahkan file
                $foto->move($path, $filename);

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
        $produk = ProdukModel::with(['warnaProduk', 'ukuran'])->findOrFail($id);
        $kategori = KategoriModel::all();
        $bahan = BahanModel::all();
        return view('produk.edit', compact('produk', 'kategori', 'bahan'));
    }

    public function update(Request $request, $id)
    {
        $produk = ProdukModel::findOrFail($id);
        $produk->update($request->only(['kategori_id', 'bahan_id', 'nama_produk', 'deskripsi']));

        // Update warna dan ukuran - sederhana: hapus lalu insert ulang
        WarnaProdukModel::where('produk_id', $id)->delete();
        UkuranProdukModel::where('produk_id', $id)->delete();

        if ($request->has('warna')) {
            foreach ($request->warna as $kode) {
                WarnaProdukModel::create([
                    'produk_id' => $id,
                    'kode_warna' => $kode,
                ]);
            }
        }

        if ($request->has('ukuran')) {
            foreach ($request->ukuran as $ukuran) {
                UkuranProdukModel::create([
                    'produk_id' => $id,
                    'ukuran' => $ukuran,
                ]);
            }
        }

        return redirect('/produk')->with('success', 'Produk berhasil diperbarui');
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
